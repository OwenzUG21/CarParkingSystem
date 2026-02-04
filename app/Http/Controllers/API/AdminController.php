<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\ParkingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function getAllBookings(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservations = Reservation::with(['user', 'parkingLocation', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'customer' => $reservation->user->name ?? 'Unknown',
                    'customer_email' => $reservation->user->email ?? '',
                    'spot' => $reservation->parkingLocation->name ?? 'Unknown',
                    'vehicle' => $reservation->vehicle,
                    'start_time' => $reservation->start_time,
                    'end_time' => $reservation->end_time,
                    'date' => $reservation->date->format('Y-m-d'),
                    'duration' => $this->calculateDuration($reservation->start_time, $reservation->end_time),
                    'amount' => (float) $reservation->amount,
                    'status' => $reservation->status,
                    'created_at' => $reservation->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['data' => $reservations]);
    }

    public function getAllPayments(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payments = Payment::with(['user', 'reservation.parkingLocation'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'customer' => $payment->user->name ?? 'Unknown',
                    'customer_email' => $payment->user->email ?? '',
                    'reservation_id' => $payment->reservation_id,
                    'parking' => $payment->reservation->parkingLocation->name ?? 'Unknown Parking',
                    'amount' => (float) $payment->amount,
                    'method' => $payment->method,
                    'status' => $payment->status,
                    'phone' => $payment->phone,
                    'provider' => $payment->provider,
                    'card_last4' => $payment->card_last4,
                    'date' => $payment->created_at->format('Y-m-d'),
                    'time' => $payment->created_at->format('H:i'),
                    'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['data' => $payments]);
    }

    public function getStats(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $now = Carbon::now();

        // Active bookings count - bookings that are currently active (between start and end time)
        $activeBookings = Reservation::where('status', '!=', 'cancelled')
            ->where(function($query) use ($now) {
                $query->where('status', 'active')
                    ->orWhere(function($q) use ($now) {
                        $q->where('status', 'upcoming')
                            ->where('date', '<=', $now->format('Y-m-d'))
                            ->whereRaw("CONCAT(date, ' ', start_time) <= ?", [$now->format('Y-m-d H:i:s')])
                            ->whereRaw("CONCAT(date, ' ', end_time) >= ?", [$now->format('Y-m-d H:i:s')]);
                    });
            })
            ->count();

        // Today's revenue
        $todayRevenue = Payment::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('amount');

        // Yesterday's revenue
        $yesterdayRevenue = Payment::whereDate('created_at', $yesterday)
            ->where('status', 'completed')
            ->sum('amount');

        // Revenue change
        $revenueChange = $yesterdayRevenue > 0 
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : 0;

        // Occupancy rate (active bookings / total parking spots)
        $totalSpots = ParkingLocation::sum('total');
        $occupiedSpots = Reservation::where('status', '!=', 'cancelled')
            ->where(function($query) use ($now) {
                $query->where('status', 'active')
                    ->orWhere(function($q) use ($now) {
                        $q->where('status', 'upcoming')
                            ->where('date', '<=', $now->format('Y-m-d'))
                            ->whereRaw("CONCAT(date, ' ', start_time) <= ?", [$now->format('Y-m-d H:i:s')])
                            ->whereRaw("CONCAT(date, ' ', end_time) >= ?", [$now->format('Y-m-d H:i:s')]);
                    });
            })
            ->count();
        
        $occupancyRate = $totalSpots > 0 ? round(($occupiedSpots / $totalSpots) * 100, 1) : 0;

        // Average duration
        $avgDuration = Reservation::where('status', '!=', 'cancelled')
            ->get()
            ->map(function($r) {
                return $this->calculateDurationHours($r->start_time, $r->end_time);
            })
            ->filter()
            ->average();

        // Revenue by payment method (last 30 days)
        $revenueByMethod = Payment::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select('method', DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->method => (float) $item->total];
            })
            ->toArray();

        // Bookings trend (last 7 days)
        $bookingsTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Reservation::whereDate('created_at', $date)->count();
            $bookingsTrend[] = [
                'date' => $date->format('D'),
                'count' => $count
            ];
        }

        // Revenue trend (last 6 months)
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            $revenueTrend[] = [
                'month' => $date->format('M'),
                'revenue' => (float) $revenue
            ];
        }

        return response()->json([
            'data' => [
                'active_bookings' => $activeBookings,
                'today_revenue' => (float) $todayRevenue,
                'yesterday_revenue' => (float) $yesterdayRevenue,
                'revenue_change' => $revenueChange,
                'occupancy_rate' => $occupancyRate,
                'avg_duration' => round($avgDuration ?: 0, 1),
                'revenue_by_method' => $revenueByMethod,
                'bookings_trend' => $bookingsTrend,
                'revenue_trend' => $revenueTrend,
            ]
        ]);
    }

    private function calculateDuration($startTime, $endTime)
    {
        if (!$startTime || !$endTime) return 'N/A';
        
        try {
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            $hours = $start->diffInHours($end);
            $minutes = $start->diffInMinutes($end) % 60;
            
            if ($hours > 0) {
                return $hours . ($minutes > 0 ? 'h ' . $minutes . 'm' : 'h');
            }
            return $minutes . 'm';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function calculateDurationHours($startTime, $endTime)
    {
        if (!$startTime || !$endTime) return null;
        
        try {
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            return $start->diffInHours($end, true);
        } catch (\Exception $e) {
            return null;
        }
    }
}

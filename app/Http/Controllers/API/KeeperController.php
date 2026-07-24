<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KeeperAssignment;
use App\Models\ParkingLocation;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeeperController extends Controller
{
    /**
     * Ensure the authenticated user is a Keeper.
     */
    protected function ensureKeeper(Request $request): User
    {
        $user = $request->user();

        if (!$user || !$user->isKeeper()) {
            abort(403, 'Unauthorized. Keeper access required.');
        }

        return $user;
    }

    /**
     * Get the keeper's current lot and live occupancy.
     */
    public function lot(Request $request)
    {
        $keeper = $this->ensureKeeper($request);

        $assignment = KeeperAssignment::with('parkingLocation')
            ->where('user_id', $keeper->id)
            ->orderByRaw("CASE status WHEN 'active' THEN 1 WHEN 'pending' THEN 2 WHEN 'inactive' THEN 3 ELSE 4 END")
            ->orderByDesc('created_at')
            ->first();

        if (!$assignment || !$assignment->parkingLocation) {
            return response()->json([
                'data' => [
                    'lot' => null,
                    'live_occupancy' => null,
                ],
            ]);
        }

        $lot = $assignment->parkingLocation;
        $now = Carbon::now();

        $totalSpots = (int) $lot->total;

        $activeBookings = Reservation::where('parking_location_id', $lot->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($now) {
                $query->where('status', 'active')
                    ->orWhere(function ($q) use ($now) {
                        $q->where('status', 'upcoming')
                            ->where('date', '<=', $now->format('Y-m-d'))
                            ->whereRaw("CONCAT(date, ' ', start_time) <= ?", [$now->format('Y-m-d H:i:s')])
                            ->whereRaw("CONCAT(date, ' ', end_time) >= ?", [$now->format('Y-m-d H:i:s')]);
                    });
            })
            ->count();

        $occupied = $activeBookings;
        $available = max($totalSpots - $occupied, 0);

        return response()->json([
            'data' => [
                'lot' => $lot,
                'assignment' => [
                    'id' => $assignment->id,
                    'status' => $assignment->status,
                ],
                'live_occupancy' => [
                    'total' => $totalSpots,
                    'occupied' => $occupied,
                    'available' => $available,
                ],
            ],
        ]);
    }

    /**
     * Active bookings for the keeper's assigned lot.
     */
    public function activeBookings(Request $request)
    {
        $keeper = $this->ensureKeeper($request);

        $assignment = KeeperAssignment::where('user_id', $keeper->id)
            ->orderByRaw("CASE status WHEN 'active' THEN 1 WHEN 'pending' THEN 2 WHEN 'inactive' THEN 3 ELSE 4 END")
            ->orderByDesc('created_at')
            ->first();

        if (!$assignment) {
            return response()->json(['data' => []]);
        }

        $lotId = $assignment->parking_location_id;
        $now = Carbon::now();

        $bookings = Reservation::where('parking_location_id', $lotId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($now) {
                $query->where('status', 'active')
                    ->orWhere(function ($q) use ($now) {
                        $q->where('status', 'upcoming')
                            ->where('date', '<=', $now->format('Y-m-d'))
                            ->whereRaw("CONCAT(date, ' ', start_time) <= ?", [$now->format('Y-m-d H:i:s')])
                            ->whereRaw("CONCAT(date, ' ', end_time) >= ?", [$now->format('Y-m-d H:i:s')]);
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return response()->json(['data' => $bookings]);
    }

    /**
     * Manual check-in by license plate.
     */
    public function checkIn(Request $request)
    {
        $keeper = $this->ensureKeeper($request);

        $data = $request->validate([
            'parking_location_id' => 'required|integer|exists:parking_locations,id',
            'license_plate' => 'required|string|max:64',
        ]);

        $lot = ParkingLocation::findOrFail($data['parking_location_id']);

        // ensure keeper is assigned to this lot
        $assignment = KeeperAssignment::where('user_id', $keeper->id)
            ->where('parking_location_id', $lot->id)
            ->first();

        if (!$assignment) {
            return response()->json(['message' => 'You are not assigned to this lot.'], 403);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        $plate = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $data['license_plate']));

        // Try to find an existing reservation for this plate (avoid duplicates)
        $reservation = Reservation::where('parking_location_id', $lot->id)
            ->whereRaw("REPLACE(REPLACE(UPPER(vehicle), ' ', ''), '-', '') = ?", [$plate])
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('created_at')
            ->first();

        if ($reservation) {
            $reservationDate = optional($reservation->date)->format('Y-m-d');
            if ($reservationDate && $reservationDate !== $today) {
                return response()->json([
                    'message' => "Reservation exists for {$reservationDate}. Check-in is only allowed on the reservation date.",
                ], 409);
            }

            if ($reservation->status === 'completed') {
                return response()->json([
                    'message' => 'This reservation is already completed.',
                ], 409);
            }

            if ($reservation->status === 'upcoming') {
                $reservation->start_time = $now->format('H:i:s');
                $reservation->status = 'active';
                $reservation->save();
            }
        } else {
            $reservation = Reservation::create([
                'user_id' => $keeper->id,
                'parking_location_id' => $lot->id,
                'date' => $today,
                'start_time' => $now->format('H:i:s'),
                'end_time' => $now->copy()->addHours(1)->format('H:i:s'),
                'vehicle' => $data['license_plate'],
                'amount' => 0,
                'status' => 'active',
                'booking_date' => $now,
            ]);
        }

        return response()->json([
            'message' => 'Check-in recorded.',
            'data' => $reservation,
        ]);
    }

    /**
     * Manual check-out by license plate.
     */
    public function checkOut(Request $request)
    {
        $keeper = $this->ensureKeeper($request);

        $data = $request->validate([
            'parking_location_id' => 'required|integer|exists:parking_locations,id',
            'license_plate' => 'required|string|max:64',
        ]);

        $lot = ParkingLocation::findOrFail($data['parking_location_id']);

        $assignment = KeeperAssignment::where('user_id', $keeper->id)
            ->where('parking_location_id', $lot->id)
            ->first();

        if (!$assignment) {
            return response()->json(['message' => 'You are not assigned to this lot.'], 403);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        $plate = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $data['license_plate']));

        $reservation = Reservation::where('parking_location_id', $lot->id)
            ->whereDate('date', $today)
            ->whereRaw("REPLACE(REPLACE(UPPER(vehicle), ' ', ''), '-', '') = ?", [$plate])
            ->whereIn('status', ['upcoming', 'active'])
            ->orderByDesc('created_at')
            ->first();

        if (!$reservation) {
            return response()->json(['message' => 'No active reservation found for this vehicle.'], 404);
        }

        $reservation->end_time = $now->format('H:i:s');
        $reservation->status = 'completed';
        $reservation->save();

        return response()->json([
            'message' => 'Check-out recorded.',
            'data' => $reservation,
        ]);
    }

    /**
     * Check payment status by license plate for keeper's lot.
     */
    public function paymentStatus(Request $request)
    {
        $keeper = $this->ensureKeeper($request);

        $data = $request->validate([
            'license_plate' => 'required|string|max:64',
        ]);

        $assignment = KeeperAssignment::where('user_id', $keeper->id)
            ->orderByRaw("CASE status WHEN 'active' THEN 1 WHEN 'pending' THEN 2 WHEN 'inactive' THEN 3 ELSE 4 END")
            ->orderByDesc('created_at')
            ->first();

        if (!$assignment) {
            return response()->json(['message' => 'No lot assigned.'], 400);
        }

        $lotId = $assignment->parking_location_id;

        $plate = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $data['license_plate']));
        $reservation = Reservation::where('parking_location_id', $lotId)
            ->whereRaw("REPLACE(REPLACE(UPPER(vehicle), ' ', ''), '-', '') = ?", [$plate])
            ->orderByDesc('created_at')
            ->first();

        if (!$reservation) {
            return response()->json([
                'data' => [
                    'paid' => false,
                    'message' => 'No reservation found for this plate.',
                ],
            ]);
        }

        $payment = Payment::where('reservation_id', $reservation->id)
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'data' => [
                'paid' => (bool) $payment,
                'reservation_id' => $reservation->id,
                'amount' => $payment ? (float) $payment->amount : 0.0,
                'method' => $payment?->method,
                'paid_at' => $payment?->created_at?->toDateTimeString(),
            ],
        ]);
    }
}

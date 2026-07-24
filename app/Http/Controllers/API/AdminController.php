<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Main Admin: create a new parking lot and optionally assign a Lot Manager.
     *
     * Flow:
     * - Admin enters lot details (address, GPS coordinates, total slots, price).
     * - Admin either selects existing manager (manager_id) or
     *   enters new manager details (manager_email, manager_phone, manager_name).
     * - System creates/updates the manager account with an initial password
     *   (email prefix before @, or phone number) and marks must_change_password.
     * - System initializes parking slots for the lot based on total slots.
     */
    public function createLotWithManager(Request $request)
    {
        // Ensure this is the Main Admin
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'total' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'features' => 'nullable',
            'manager_id' => 'nullable|exists:users,id',
            'manager_email' => 'nullable|email',
            'manager_phone' => 'nullable|string|max:32',
            'manager_name' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        // features may arrive as JSON string (FormData). Normalize it.
        if (isset($data['features']) && is_string($data['features'])) {
            $decoded = json_decode($data['features'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['features'] = $decoded;
            }
        }

        $manager = null;
        $managerInitialPassword = null;

        // Determine or create manager if provided
        if (!empty($data['manager_id'])) {
            $manager = User::findOrFail($data['manager_id']);

            // Ensure they have the correct role
            if ($manager->role !== 'lot_manager') {
                $manager->role = 'lot_manager';
                $manager->save();
            }
        } elseif (!empty($data['manager_email']) || !empty($data['manager_phone'])) {
            $managerEmail = $data['manager_email'] ?? null;
            $managerPhone = $data['manager_phone'] ?? null;

            $manager = User::where('email', $managerEmail)
                ->orWhere('phone', $managerPhone)
                ->first();

            // Compute initial password: email prefix before @, else phone
            $initialPassword = null;
            if ($managerEmail) {
                $prefix = strstr($managerEmail, '@', true);
                if ($prefix) {
                    $initialPassword = $prefix;
                }
            }
            if (!$initialPassword && $managerPhone) {
                $initialPassword = preg_replace('/\s+/', '', $managerPhone);
            }
            // Final safety fallback
            if (!$initialPassword) {
                $initialPassword = 'ChangeMe123!';
            }

            $managerInitialPassword = $initialPassword;

            if (!$manager) {
                $manager = User::create([
                    'name' => $data['manager_name'] ?? 'Lot Manager',
                    'email' => $managerEmail ?? ('manager_' . uniqid() . '@lot.local'),
                    'phone' => $managerPhone,
                    'password' => Hash::make($initialPassword),
                    'role' => 'lot_manager',
                    'is_admin' => false,
                    'must_change_password' => true,
                ]);
            } else {
                // Update existing user to become a Lot Manager and reset their initial password
                $manager->update([
                    'name' => $data['manager_name'] ?? $manager->name,
                    'email' => $managerEmail ?? $manager->email,
                    'phone' => $managerPhone ?? $manager->phone,
                    'password' => Hash::make($initialPassword),
                    'role' => 'lot_manager',
                    'is_admin' => false,
                    'must_change_password' => true,
                ]);
            }
        }

        // Handle optional image upload or path
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lot-images', 'public');
        }
        $directImagePath = $data['image_path'] ?? null;

        // Create the parking location and initialize its slots
        $location = null;

        DB::transaction(function () use (&$location, $data, $manager, $imagePath, $directImagePath) {
            $finalImage = null;
            if ($directImagePath) {
                // Use the path provided by admin, e.g. "park/bd.jpg"
                $finalImage = $directImagePath;
            } elseif ($imagePath) {
                // Use stored image from /storage
                $finalImage = 'storage/' . $imagePath;
            }

            $location = ParkingLocation::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'rating' => $data['rating'] ?? 0,
                'price' => $data['price'],
                'available' => $data['total'],
                'total' => $data['total'],
                'distance' => 0,
                'image' => $finalImage,
                'features' => $data['features'] ?? [],
                'is_active' => true,
                'manager_id' => $manager?->id,
            ]);

            // Initialize basic slots S-1..S-total for this lot
            for ($i = 1; $i <= $data['total']; $i++) {
                ParkingSlot::create([
                    'parking_location_id' => $location->id,
                    'label' => 'S-' . $i,
                    'is_vip' => false,
                    'is_ev_only' => false,
                    'is_under_maintenance' => false,
                ]);
            }
        });

        return response()->json([
            'message' => 'Parking lot initialized successfully.',
            'data' => [
                'location' => $location->loadCount('slots'),
                'manager' => $manager,
                // Expose the initial password so the Admin can share it out-of-band.
                // In production you’d normally send this via secure email/SMS instead.
                'manager_initial_password' => $managerInitialPassword,
            ],
        ], 201);
    }

    /**
     * List all users who can be (or already are) Lot Managers.
     * Used by the Main Admin UI to populate the "Assign Manager" dropdown.
     */
    public function getManagers(Request $request)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $managers = User::where('role', 'lot_manager')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'role', 'is_active', 'must_change_password']);

        return response()->json(['data' => $managers]);
    }

    /**
     * List all parking locations (including inactive) for admin management.
     */
    public function getLots(Request $request)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lots = ParkingLocation::with('manager')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $lots]);
    }

    /**
     * Update a parking lot (admin-level: name, address, price, rating, image, is_active, manager).
     */
    public function updateLot(Request $request, $id)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lot = ParkingLocation::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'lat' => 'sometimes|numeric|between:-90,90',
            'lng' => 'sometimes|numeric|between:-180,180',
            'price' => 'sometimes|numeric|min:0',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'total' => 'sometimes|integer|min:1',
            'available' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'manager_id' => 'nullable|exists:users,id',
            'image' => 'nullable|string|max:255',
        ]);

        $lot->fill($data);
        $lot->save();

        return response()->json([
            'message' => 'Lot updated successfully.',
            'data' => $lot->load('manager'),
        ]);
    }

    /**
     * Delete a parking lot.
     */
    public function deleteLot(Request $request, $id)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lot = ParkingLocation::findOrFail($id);
        $lot->delete();

        return response()->json(['message' => 'Lot deleted successfully.']);
    }

    public function createManager(Request $request)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:32',
        ]);

        $prefix = strstr($data['email'], '@', true);
        $initialPassword = $prefix ?: ($data['phone'] ? preg_replace('/\s+/', '', $data['phone']) : 'ChangeMe123!');

        $manager = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($initialPassword),
            'role' => 'lot_manager',
            'is_admin' => false,
            'is_active' => true,
            'must_change_password' => true,
        ]);

        return response()->json([
            'message' => 'Lot Manager created successfully.',
            'data' => [
                'manager' => $manager,
                'initial_password' => $initialPassword,
            ],
        ], 201);
    }

    public function updateManager(Request $request, $id)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $manager = User::where('role', 'lot_manager')->findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $manager->id,
            'phone' => 'nullable|string|max:32',
            'is_active' => 'sometimes|boolean',
            'reset_password' => 'sometimes|boolean',
        ]);

        if (!empty($data['reset_password']) && $data['reset_password'] === true) {
            $email = $data['email'] ?? $manager->email;
            $phone = $data['phone'] ?? $manager->phone;
            $prefix = $email ? strstr($email, '@', true) : null;
            $initialPassword = $prefix ?: ($phone ? preg_replace('/\s+/', '', $phone) : 'ChangeMe123!');

            $manager->password = Hash::make($initialPassword);
            $manager->must_change_password = true;
        }

        $manager->fill(collect($data)->except(['reset_password'])->toArray());
        $manager->save();

        return response()->json([
            'message' => 'Lot Manager updated successfully.',
            'data' => $manager->only(['id', 'name', 'email', 'phone', 'role', 'is_active', 'must_change_password']),
        ]);
    }

    public function deleteManager(Request $request, $id)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $manager = User::where('role', 'lot_manager')->findOrFail($id);

        // Lots will be unassigned because parking_locations.manager_id is nullOnDelete
        $manager->delete();

        return response()->json(['message' => 'Lot Manager deleted successfully.']);
    }

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

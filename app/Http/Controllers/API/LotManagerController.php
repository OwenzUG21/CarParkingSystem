<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KeeperAssignment;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingValidation;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LotManagerController extends Controller
{
    /**
     * Ensure the authenticated user is a Lot Manager.
     */
    protected function ensureLotManager(Request $request): User
    {
        $user = $request->user();

        if (!$user || !$user->isLotManager()) {
            abort(403, 'Unauthorized. Lot Manager access required.');
        }

        return $user;
    }

    /**
     * Dashboard stats for a Lot Manager (per their managed locations).
     */
    public function dashboard(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $locationIds = $manager->managedLocations()->pluck('id');

        if ($locationIds->isEmpty()) {
            return response()->json([
                'data' => [
                    'active_bookings' => 0,
                    'today_revenue' => 0.0,
                    'yesterday_revenue' => 0.0,
                    'revenue_change' => 0.0,
                    'occupancy_rate' => 0.0,
                    'avg_duration' => 0.0,
                ],
            ]);
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $now = Carbon::now();

        // Active bookings for manager's locations
        $activeBookings = Reservation::whereIn('parking_location_id', $locationIds)
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

        // Payments joined through reservations for manager's locations
        $todayRevenue = Payment::where('status', 'completed')
            ->whereDate('payments.created_at', $today)
            ->whereHas('reservation', function ($q) use ($locationIds) {
                $q->whereIn('parking_location_id', $locationIds);
            })
            ->sum('amount');

        $yesterdayRevenue = Payment::where('status', 'completed')
            ->whereDate('payments.created_at', $yesterday)
            ->whereHas('reservation', function ($q) use ($locationIds) {
                $q->whereIn('parking_location_id', $locationIds);
            })
            ->sum('amount');

        $revenueChange = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : 0;

        // Occupancy based on active reservations vs total slots for manager's locations
        $totalSpots = ParkingLocation::whereIn('id', $locationIds)->sum('total');

        $occupiedSpots = Reservation::whereIn('parking_location_id', $locationIds)
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

        $occupancyRate = $totalSpots > 0 ? round(($occupiedSpots / $totalSpots) * 100, 1) : 0;

        // Average duration for reservations at manager's locations
        $avgDuration = Reservation::whereIn('parking_location_id', $locationIds)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($r) {
                if (!$r->start_time || !$r->end_time) {
                    return null;
                }
                try {
                    $start = Carbon::parse($r->start_time);
                    $end = Carbon::parse($r->end_time);
                    return $start->diffInHours($end, true);
                } catch (\Exception $e) {
                    return null;
                }
            })
            ->filter()
            ->average();

        // Bookings trend (last 7 days) for manager's lots
        $bookingsTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Reservation::whereIn('parking_location_id', $locationIds)
                ->whereDate('created_at', $date)
                ->count();
            $bookingsTrend[] = [
                'date' => $date->format('D'),
                'count' => $count,
            ];
        }

        // Revenue trend (last 6 months) for manager's lots
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'completed')
                ->whereHas('reservation', function ($q) use ($locationIds) {
                    $q->whereIn('parking_location_id', $locationIds);
                })
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            $revenueTrend[] = [
                'month' => $date->format('M'),
                'revenue' => (float) $revenue,
            ];
        }

        // Revenue by lot (last 30 days)
        $revenueByLot = Payment::where('status', 'completed')
            ->whereHas('reservation', function ($q) use ($locationIds) {
                $q->whereIn('parking_location_id', $locationIds);
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select('reservation_id', DB::raw('SUM(amount) as total'))
            ->groupBy('reservation_id')
            ->get()
            ->mapToGroups(function ($row) {
                $reservation = Reservation::find($row->reservation_id);
                if (!$reservation) {
                    return [];
                }
                return [$reservation->parking_location_id => (float) $row->total];
            })
            ->map(function ($amounts, $locationId) {
                $location = ParkingLocation::find($locationId);
                return [
                    'location_id' => (int) $locationId,
                    'name' => $location?->name ?? 'Unknown',
                    'revenue' => array_sum($amounts->toArray()),
                ];
            })
            ->values()
            ->toArray();

        // Keeper summary (simple performance overview)
        $keeperAssignments = KeeperAssignment::whereIn('parking_location_id', $locationIds)->get();
        $keepersSummary = [
            'total' => $keeperAssignments->count(),
            'pending' => $keeperAssignments->where('status', 'pending')->count(),
            'active' => $keeperAssignments->where('status', 'active')->count(),
        ];

        return response()->json([
            'data' => [
                'active_bookings' => $activeBookings,
                'today_revenue' => (float) $todayRevenue,
                'yesterday_revenue' => (float) $yesterdayRevenue,
                'revenue_change' => $revenueChange,
                'occupancy_rate' => $occupancyRate,
                'avg_duration' => round($avgDuration ?: 0, 1),
                'bookings_trend' => $bookingsTrend,
                'revenue_trend' => $revenueTrend,
                'revenue_by_lot' => $revenueByLot,
                'keepers_summary' => $keepersSummary,
            ],
        ]);
    }

    /**
     * List parking locations managed by this Lot Manager.
     */
    public function lots(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $locations = $manager->managedLocations()
            ->withCount('slots')
            ->get();

        return response()->json([
            'data' => $locations,
        ]);
    }

    /**
     * Update basic lot details (name, price, image, rating) for a manager-owned lot.
     */
    public function updateLot(Request $request, $id)
    {
        $manager = $this->ensureLotManager($request);

        $location = ParkingLocation::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'lat' => 'sometimes|numeric|between:-90,90',
            'lng' => 'sometimes|numeric|between:-180,180',
            'price' => 'sometimes|numeric|min:0',
            'rating' => 'sometimes|numeric|min:0|max:5',
        ];

        if ($request->hasFile('image')) {
            $rules['image'] = 'sometimes|file|image|max:5120';
        } else {
            $rules['image'] = 'sometimes|nullable|string|max:255';
        }

        $data = $request->validate($rules);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('lots', 'public');
            $data['image'] = Storage::url($path);
        }

        $location->fill($data);
        $location->save();

        return response()->json([
            'message' => 'Lot updated successfully.',
            'data' => $location,
        ]);
    }

    /**
     * Get active bookings for a specific manager-owned lot.
     */
    public function activeBookings(Request $request, $id)
    {
        $manager = $this->ensureLotManager($request);

        $location = ParkingLocation::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $now = Carbon::now();

        $bookings = Reservation::where('parking_location_id', $location->id)
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

        return response()->json([
            'data' => $bookings,
        ]);
    }

    /**
     * List all bookings for manager-owned lots.
     */
    public function bookings(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $locationIds = $manager->managedLocations()->pluck('id');

        if ($locationIds->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $bookings = Reservation::with(['user', 'parkingLocation', 'payment'])
            ->whereIn('parking_location_id', $locationIds)
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Reservation $reservation) {
                return [
                    'id' => $reservation->id,
                    'customer' => $reservation->user?->name ?? 'Unknown',
                    'customer_email' => $reservation->user?->email ?? '',
                    'parking' => $reservation->parkingLocation?->name ?? 'Unknown',
                    'vehicle' => $reservation->vehicle,
                    'start_time' => $reservation->start_time,
                    'end_time' => $reservation->end_time,
                    'date' => optional($reservation->date)->format('Y-m-d'),
                    'amount' => (float) $reservation->amount,
                    'status' => $reservation->status,
                    'payment_status' => $reservation->payment?->status ?? 'pending',
                    'created_at' => $reservation->created_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['data' => $bookings]);
    }

    /**
     * Get slot configuration for a specific lot.
     */
    public function getSlots(Request $request, $id)
    {
        $manager = $this->ensureLotManager($request);

        $location = ParkingLocation::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $slots = $location->slots()->orderBy('label')->get();

        return response()->json([
            'data' => $slots,
        ]);
    }

    /**
     * Configure slots for a lot (define number and flags).
     *
     * Request options:
     * - total_slots: int (optional, used if slots array is not provided)
     * - slots: array of { label, is_vip, is_ev_only, is_under_maintenance }
     */
    public function configureSlots(Request $request, $id)
    {
        $manager = $this->ensureLotManager($request);

        $location = ParkingLocation::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $data = $request->validate([
            'total_slots' => 'nullable|integer|min:1',
            'slots' => 'nullable|array',
            'slots.*.label' => 'required_with:slots|string',
            'slots.*.is_vip' => 'sometimes|boolean',
            'slots.*.is_ev_only' => 'sometimes|boolean',
            'slots.*.is_under_maintenance' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($location, $data) {
            // Clear existing slots
            $location->slots()->delete();

            if (!empty($data['slots'])) {
                foreach ($data['slots'] as $slot) {
                    $location->slots()->create([
                        'label' => $slot['label'],
                        'is_vip' => $slot['is_vip'] ?? false,
                        'is_ev_only' => $slot['is_ev_only'] ?? false,
                        'is_under_maintenance' => $slot['is_under_maintenance'] ?? false,
                    ]);
                }
            } elseif (!empty($data['total_slots'])) {
                // Auto-generate simple labels S-1, S-2, ...
                for ($i = 1; $i <= $data['total_slots']; $i++) {
                    $location->slots()->create([
                        'label' => 'S-' . $i,
                        'is_vip' => false,
                        'is_ev_only' => false,
                        'is_under_maintenance' => false,
                    ]);
                }
            }
        });

        // Refresh and return updated slots
        $location->refresh();

        return response()->json([
            'message' => 'Slots configured successfully.',
            'data' => $location->slots()->orderBy('label')->get(),
        ]);
    }

    /**
     * List keepers for all lots managed by this Lot Manager.
     */
    public function keepers(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $locationIds = $manager->managedLocations()->pluck('id');

        $assignments = KeeperAssignment::with(['keeper', 'parkingLocation'])
            ->whereIn('parking_location_id', $locationIds)
            ->get()
            ->map(function (KeeperAssignment $assignment) {
                return [
                    'id' => $assignment->id,
                    'keeper_id' => $assignment->keeper?->id,
                    'name' => $assignment->keeper?->name,
                    'email' => $assignment->keeper?->email,
                    'phone' => $assignment->keeper?->phone,
                    'parking_location_id' => $assignment->parkingLocation?->id,
                    'parking_location_name' => $assignment->parkingLocation?->name,
                    'status' => $assignment->status,
                    'invite_sent_at' => optional($assignment->invite_sent_at)->toDateTimeString(),
                    'created_at' => optional($assignment->created_at)->toDateTimeString(),
                ];
            });

        return response()->json([
            'data' => $assignments,
        ]);
    }

    /**
     * Add a new Keeper under this Lot Manager.
     *
     * - The keeper gets a "pending" account.
     * - Initial password is their phone number.
     */
    public function storeKeeper(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'required|string|max:32',
            'parking_location_id' => 'required|integer|exists:parking_locations,id',
        ]);

        $location = ParkingLocation::where('id', $validated['parking_location_id'])
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        // Either find an existing user by phone or create a new one
        $keeper = User::where('phone', $validated['phone'])->first();

        $initialPassword = $validated['phone'];

        $keeperEmail = $validated['email'] ?? null;
        if (!$keeperEmail) {
            $keeperEmail = $keeper?->email;
        }
        if (!$keeperEmail) {
            // Generate a synthetic email since users.email is required/unique
            $keeperEmail = 'keeper_' . preg_replace('/\D/', '', $validated['phone']) . '@keeper.local';
        }

        if ($keeper) {
            $request->validate([
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($keeper->id),
                ],
            ]);
        } else {
            $request->validate([
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email'),
                ],
            ]);
        }

        if (!$keeper) {
            $keeper = User::create([
                'name' => $validated['name'],
                'email' => $keeperEmail,
                'phone' => $validated['phone'],
                'password' => Hash::make($initialPassword),
                'role' => 'keeper',
                'must_change_password' => true,
            ]);
        } else {
            // Promote existing user to keeper and reset their initial password for this role
            $keeper->update([
                'name' => $validated['name'],
                'email' => $keeperEmail,
                'password' => Hash::make($initialPassword),
                'role' => 'keeper',
                'must_change_password' => true,
            ]);
        }

        // Create or update assignment for this location
        $assignment = KeeperAssignment::updateOrCreate(
            [
                'user_id' => $keeper->id,
                'parking_location_id' => $location->id,
            ],
            [
                'status' => 'pending',
                'invite_token' => null,
                'invite_sent_at' => null,
            ]
        );

        return response()->json([
            'message' => 'Keeper account created and assigned successfully. Share the initial PIN with the keeper so they can log in and set a new password.',
            'data' => [
                'keeper_id' => $keeper->id,
                'name' => $keeper->name,
                'email' => $keeper->email,
                'phone' => $keeper->phone,
                'parking_location_id' => $location->id,
                'parking_location_name' => $location->name,
                'status' => $assignment->status,
                'initial_password' => $initialPassword,
            ],
        ], 201);
    }

    /**
     * Recent keeper activity for this Lot Manager's lots.
     * Derived from reservations created by keeper accounts (manual check-ins).
     */
    public function keeperActivity(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $locationIds = $manager->managedLocations()->pluck('id');

        if ($locationIds->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $activities = Reservation::with(['user', 'parkingLocation'])
            ->whereIn('parking_location_id', $locationIds)
            ->whereHas('user', function ($q) {
                $q->where('role', 'keeper');
            })
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function (Reservation $reservation) {
                return [
                    'reservation_id' => $reservation->id,
                    'keeper_id' => $reservation->user?->id,
                    'keeper_name' => $reservation->user?->name,
                    'keeper_email' => $reservation->user?->email,
                    'parking_location_id' => $reservation->parkingLocation?->id,
                    'parking_location_name' => $reservation->parkingLocation?->name,
                    'license_plate' => $reservation->vehicle,
                    'status' => $reservation->status,
                    'created_at' => optional($reservation->created_at)->toDateTimeString(),
                ];
            });

        return response()->json(['data' => $activities]);
    }

    /**
     * Update keeper details and/or their assignment (under this Lot Manager).
     */
    public function updateKeeper(Request $request, $assignmentId)
    {
        $manager = $this->ensureLotManager($request);

        $assignment = KeeperAssignment::with(['keeper', 'parkingLocation'])
            ->where('id', $assignmentId)
            ->whereHas('parkingLocation', function ($q) use ($manager) {
                $q->where('manager_id', $manager->id);
            })
            ->firstOrFail();

        $keeper = $assignment->keeper;
        if (!$keeper) {
            abort(404, 'Keeper not found.');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($keeper->id),
            ],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:32',
                Rule::unique('users', 'phone')->ignore($keeper->id),
            ],
            'status' => 'sometimes|in:pending,active,inactive',
            'parking_location_id' => 'sometimes|integer|exists:parking_locations,id',
        ]);

        if (array_key_exists('parking_location_id', $validated)) {
            $newLotId = (int) $validated['parking_location_id'];
            ParkingLocation::where('id', $newLotId)
                ->where('manager_id', $manager->id)
                ->firstOrFail();

            if ($assignment->parking_location_id !== $newLotId) {
                $existing = KeeperAssignment::where('user_id', $keeper->id)
                    ->where('parking_location_id', $newLotId)
                    ->first();

                if ($existing && $existing->id !== $assignment->id) {
                    if (array_key_exists('status', $validated)) {
                        $existing->status = $validated['status'];
                        $existing->save();
                    }
                    $assignment->delete();
                    $assignment = $existing->fresh(['keeper', 'parkingLocation']);
                } else {
                    $assignment->parking_location_id = $newLotId;
                }
            }
        }

        if (array_key_exists('status', $validated)) {
            $assignment->status = $validated['status'];
        }

        $assignment->save();

        $keeperUpdates = array_intersect_key($validated, array_flip(['name', 'email', 'phone']));
        if (!empty($keeperUpdates)) {
            $keeper->update($keeperUpdates);
        }

        return response()->json([
            'message' => 'Keeper updated successfully.',
            'data' => [
                'id' => $assignment->id,
                'keeper_id' => $keeper->id,
                'name' => $keeper->name,
                'email' => $keeper->email,
                'phone' => $keeper->phone,
                'parking_location_id' => $assignment->parking_location_id,
                'parking_location_name' => $assignment->parkingLocation?->name,
                'status' => $assignment->status,
            ],
        ]);
    }

    /**
     * Remove a keeper assignment under this Lot Manager.
     */
    public function destroyKeeper(Request $request, $assignmentId)
    {
        $manager = $this->ensureLotManager($request);

        $assignment = KeeperAssignment::with(['keeper', 'parkingLocation'])
            ->where('id', $assignmentId)
            ->whereHas('parkingLocation', function ($q) use ($manager) {
                $q->where('manager_id', $manager->id);
            })
            ->firstOrFail();

        $keeper = $assignment->keeper;

        $assignment->delete();

        if ($keeper) {
            $remaining = KeeperAssignment::where('user_id', $keeper->id)->count();
            if ($remaining === 0 && $keeper->role === 'keeper') {
                $keeper->role = 'user';
                $keeper->save();
            }
        }

        return response()->json([
            'message' => 'Keeper removed successfully.',
        ]);
    }

    /**
     * Issue a parking validation (e.g., “Mall customers get 2 hours free”)
     * for one of the manager's locations.
     */
    public function createValidation(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $validated = $request->validate([
            'parking_location_id' => 'required|integer|exists:parking_locations,id',
            'business_name' => 'required|string|max:255',
            'free_minutes' => 'nullable|integer|min:1',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $location = ParkingLocation::where('id', $validated['parking_location_id'])
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        // Simple random code; can be enhanced later
        $code = strtoupper(bin2hex(random_bytes(4)));

        $validation = ParkingValidation::create([
            'parking_location_id' => $location->id,
            'business_name' => $validated['business_name'],
            'code' => $code,
            'free_minutes' => $validated['free_minutes'] ?? 120,
            'max_uses' => $validated['max_uses'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return response()->json([
            'message' => 'Validation created successfully.',
            'data' => $validation,
        ], 201);
    }

    /**
     * List validations for the manager's locations.
     */
    public function validations(Request $request)
    {
        $manager = $this->ensureLotManager($request);

        $locationIds = $manager->managedLocations()->pluck('id');

        $validations = ParkingValidation::whereIn('parking_location_id', $locationIds)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => $validations,
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

// app/Http/Controllers/API/ReservationController.php


use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\ParkingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    private const PAYMENT_WINDOW_MINUTES = 20;

    public function index(Request $request)
    {
        $this->expirePendingReservations(null, $request->user()->id);

        $reservations = $request->user()
            ->reservations()
            ->with(['parkingLocation', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $reservations
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'parking_location_id' => 'required|exists:parking_locations,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'license_plate' => 'nullable|string',
            'vehicle' => 'nullable|string',
            'total_amount' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'payment_phone' => 'nullable|string',
            'payment_provider' => 'nullable|string',
            'payment_card_last4' => 'nullable|string',
        ]);

        // Handle both field name variations
        $vehicle = $request->vehicle ?? $request->license_plate;
        $amount = $request->amount ?? $request->total_amount;

        if (!$vehicle || !$amount) {
            return response()->json([
                'message' => 'Vehicle and amount are required'
            ], 422);
        }

        return DB::transaction(function () use ($request, $vehicle, $amount) {
            $this->expirePendingReservations($request->parking_location_id);
            $location = ParkingLocation::lockForUpdate()->findOrFail($request->parking_location_id);

            if ($location->available <= 0) {
                return response()->json([
                    'message' => 'No parking slots available for this location'
                ], 409);
            }

            $location->available = max($location->available - 1, 0);
            $location->save();

            $reservation = $request->user()->reservations()->create([
                'parking_location_id' => $request->parking_location_id,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'vehicle' => $vehicle,
                'amount' => $amount,
                'status' => 'upcoming',
            ]);

            // Create pending payment record to allow pay-later flow
            $paymentMethod = $request->payment_method ?? 'Pending';
            Payment::create([
                'user_id' => $request->user()->id,
                'reservation_id' => $reservation->id,
                'amount' => $amount,
                'method' => $paymentMethod,
                'status' => 'pending',
                'phone' => $request->payment_phone,
                'provider' => $request->payment_provider,
                'card_last4' => $request->payment_card_last4,
            ]);

            return response()->json([
                'data' => $reservation->load(['parkingLocation', 'payment']),
                'message' => 'Reservation created successfully'
            ], 201);
        });
    }

    public function update(Request $request, $id)
    {
        $reservation = $request->user()->reservations()->findOrFail($id);
        $reservation->update($request->only(['status']));

        return response()->json($reservation);
    }

    public function cancel($id, Request $request)
    {
        $reservation = $request->user()->reservations()->with('payment')->findOrFail($id);
        if ($reservation->status !== 'cancelled') {
            $reservation->update(['status' => 'cancelled']);
            if ($reservation->payment && $reservation->payment->status !== 'completed') {
                $reservation->payment->status = 'failed';
                $reservation->payment->save();
            }
            $location = $reservation->parkingLocation;
            if ($location) {
                $location->available = min($location->available + 1, $location->total);
                $location->save();
            }
        }

        return response()->json([
            'data' => $reservation->load('parkingLocation'),
            'message' => 'Reservation cancelled successfully'
        ]);
    }

    private function expirePendingReservations(?int $parkingLocationId = null, ?int $userId = null): void
    {
        $cutoff = now()->subMinutes(self::PAYMENT_WINDOW_MINUTES);

        $query = Reservation::where('status', 'upcoming')
            ->where('created_at', '<=', $cutoff)
            ->where(function ($builder) {
                $builder->whereDoesntHave('payment')
                    ->orWhereHas('payment', function ($paymentQuery) {
                        $paymentQuery->where('status', '!=', 'completed');
                    });
            });

        if ($parkingLocationId) {
            $query->where('parking_location_id', $parkingLocationId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $expiredReservations = $query->with(['parkingLocation', 'payment'])->get();

        foreach ($expiredReservations as $reservation) {
            DB::transaction(function () use ($reservation) {
                if (in_array($reservation->status, ['cancelled', 'completed'], true)) {
                    return;
                }

                $reservation->status = 'cancelled';
                $reservation->save();

                if ($reservation->payment && $reservation->payment->status !== 'completed') {
                    $reservation->payment->status = 'failed';
                    $reservation->payment->save();
                }

                $location = $reservation->parkingLocation;
                if ($location) {
                    $location->available = min($location->available + 1, $location->total);
                    $location->save();
                }
            });
        }
    }
}

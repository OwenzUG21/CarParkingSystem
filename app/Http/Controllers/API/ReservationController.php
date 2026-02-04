<?php

namespace App\Http\Controllers\API;

// app/Http/Controllers/API/ReservationController.php


use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = $request->user()
            ->reservations()
            ->with('parkingLocation')
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

        $reservation = $request->user()->reservations()->create([
            'parking_location_id' => $request->parking_location_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'vehicle' => $vehicle,
            'amount' => $amount,
            'status' => 'upcoming',
        ]);

        // Create payment record with payment method details
        $paymentMethod = $request->payment_method ?? 'Credit Card';
        Payment::create([
            'user_id' => $request->user()->id,
            'reservation_id' => $reservation->id,
            'amount' => $amount,
            'method' => $paymentMethod,
            'status' => 'completed',
            'phone' => $request->payment_phone,
            'provider' => $request->payment_provider,
            'card_last4' => $request->payment_card_last4,
        ]);

        return response()->json([
            'data' => $reservation->load('parkingLocation'),
            'message' => 'Reservation created successfully'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $reservation = $request->user()->reservations()->findOrFail($id);
        $reservation->update($request->only(['status']));

        return response()->json($reservation);
    }

    public function cancel($id, Request $request)
    {
        $reservation = $request->user()->reservations()->findOrFail($id);
        $reservation->update(['status' => 'cancelled']);

        return response()->json([
            'data' => $reservation->load('parkingLocation'),
            'message' => 'Reservation cancelled successfully'
        ]);
    }
}
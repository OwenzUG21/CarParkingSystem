<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private const PAYMENT_WINDOW_MINUTES = 20;

    public function index(Request $request)
    {
        $payments = $request->user()
            ->payments()
            ->with('reservation.parkingLocation')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'reservation_id' => $payment->reservation_id,
                    'amount' => $payment->amount,
                    'method' => $payment->method,
                    'status' => $payment->status,
                    'phone' => $payment->phone,
                    'provider' => $payment->provider,
                    'card_last4' => $payment->card_last4,
                    'date' => $payment->created_at->format('Y-m-d'),
                    'time' => $payment->created_at->format('H:i'),
                    'parking' => $payment->reservation->parkingLocation->name ?? 'Unknown Parking',
                ];
            });

        return response()->json([
            'data' => $payments
        ]);
    }

    public function store(Request $request, $reservationId)
    {
        $request->validate([
            'method' => 'required|string',
            'phone' => 'nullable|string',
            'provider' => 'nullable|string',
            'card_last4' => 'nullable|string',
        ]);

        $reservation = $request->user()->reservations()->with(['payment', 'parkingLocation'])->findOrFail($reservationId);

        if ($reservation->status === 'cancelled') {
            return response()->json(['message' => 'Reservation already cancelled.'], 409);
        }

        if ($reservation->payment && $reservation->payment->status === 'completed') {
            return response()->json(['message' => 'Reservation already paid.'], 409);
        }

        $cutoff = $reservation->created_at->copy()->addMinutes(self::PAYMENT_WINDOW_MINUTES);
        if (now()->greaterThan($cutoff)) {
            $this->expireReservation($reservation);
            return response()->json(['message' => 'Payment window expired. Reservation cancelled.'], 409);
        }

        $payment = Payment::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'user_id' => $request->user()->id,
                'amount' => $reservation->amount,
                'method' => $request->method,
                'status' => 'completed',
                'phone' => $request->phone,
                'provider' => $request->provider,
                'card_last4' => $request->card_last4,
            ]
        );

        return response()->json([
            'data' => [
                'payment' => $payment,
                'reservation' => $reservation->fresh(['parkingLocation', 'payment']),
            ]
        ]);
    }

    private function expireReservation(Reservation $reservation): void
    {
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
    }
}

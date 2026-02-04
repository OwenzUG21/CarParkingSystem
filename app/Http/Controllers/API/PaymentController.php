<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
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
}

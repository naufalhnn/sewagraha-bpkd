<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\Venue;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create($id)
    {
        $booking = Booking::find($id);
        $venue = Venue::find($booking->venue_id);
        return view('pages.payment', compact('booking', 'venue'));
    }

    public function store(Request $request, $id)
    {
        $payment = Payment::where('booking_id', $id)->first();
        $payment->update([
            'status' => 'PAID',
            'paid_at' => now(),
        ]);

        $imagePath = $request->file('payment_proof')->store('payment-proof', 'public');

        PaymentProof::create([
            'payment_id' => $payment->id,
            'proof_image_path' => $imagePath,
            'uploaded_at' => now(),
        ]);

        return redirect()->route('success');
    }
}

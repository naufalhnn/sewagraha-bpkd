<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $payment_integer = preg_replace('/\D/', '', $request->total_price);


        $imagePath = $request->file('ktp_image')->store('ktp-image', 'public');

        Booking::create([
            'venue_id' => $request->venue_id,
            'user_id' => $request->user_id,
            'event_start_date' => $request->event_start_date,
            'event_end_date' => $request->event_end_date,
            'purpose' => $request->purpose,
            'total_price' => $payment_integer,
            'ktp_image_path' => $imagePath,
            'status' => 'PENDING',
        ]);

        Payment::create([
            'booking_id' => Booking::latest()->first()->id,
            'user_id' => $request->user_id,
            'total_price' => $payment_integer,
            'status' => 'PENDING',
        ]);

        return redirect()->route('payment.create', ['id' => Booking::latest()->first()->id]);
    }
}

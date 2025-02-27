<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        Booking::create([
            'venue_id' => $request->venue_id,
            'user_id' => $request->user_id,
            'event_start_date' => $request->event_start_date,
            'event_end_date' => $request->event_end_date,
            'purpose' => $request->purpose,
            'total_price' => preg_replace('/\D/', '', $request->total_price),
            'status' => 'PENDING',
        ]);

        return redirect()->route('payment.create', ['id' => Booking::latest()->first()->id]);
    }
}

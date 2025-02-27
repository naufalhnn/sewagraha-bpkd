<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index($id)
    {
        $booking = Booking::find($id);
        $venue = Venue::find($booking->venue_id);
        return view('pages.payment', compact('booking', 'venue'));
    }
}

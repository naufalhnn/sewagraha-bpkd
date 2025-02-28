<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $venues = Venue::with('venueImages')->get();
        return view('pages.index', compact('venues'));
    }

    public function details($id)
    {
        $venue = Venue::with(['venueImages', 'purposes', 'facilities'])->find($id);
        return view('pages.details', compact('venue'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function venues()
    {
        $venues = Venue::with('venueImages')->get();
        return view('pages.venues', compact('venues'));
    }

    public function success()
    {
        return view('pages.payment-success');
    }
}

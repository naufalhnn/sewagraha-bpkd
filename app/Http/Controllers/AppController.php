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
}

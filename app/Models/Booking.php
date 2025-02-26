<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'venue_id',
        'booking_code',
        'event_start_date',
        'event_end_date',
        'purpose',
        'total_price',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            if ($booking->venue_id && $booking->event_start_date && $booking->event_end_date && !$booking->total_price) {
                $startDate = Carbon::parse($booking->event_start_date);
                $endDate = Carbon::parse($booking->event_end_date);
                $daysDifference = $startDate->isSameDay($endDate) ? 1 : $startDate->diffInDays($endDate);

                $venue = Venue::find($booking->venue_id);
                if ($venue) {
                    $booking->total_price = $venue->base_price * $daysDifference;
                }
            }
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}

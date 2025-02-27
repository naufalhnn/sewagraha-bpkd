<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'address',
        'capacity',
        'base_price',
        'building_condition',
    ];

    public function venueImages()
    {
        return $this->hasMany(VenueImage::class, 'venue_id', 'id');
    }

    public function venueSchedules()
    {
        return $this->hasMany(VenueSchedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'venue_facilities', 'venue_id', 'facility_id')->withTimestamps();
    }

    public function purposes()
    {
        return $this->belongsToMany(Purpose::class, 'venue_purposes', 'venue_id', 'purpose_id')->withTimestamps();
    }
}

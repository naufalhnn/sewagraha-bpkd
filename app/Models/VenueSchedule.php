<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venue_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}

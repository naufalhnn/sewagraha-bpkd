<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purpose extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'venue_purposes' . 'purpose_id', 'venue_id')->withTimestamps();
    }
}

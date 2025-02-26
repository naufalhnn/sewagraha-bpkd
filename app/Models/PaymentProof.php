<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProof extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'proof_image_path',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'payment_code',
        'total_price',
        'status',
        'paid_at',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($payment) {
            // Only generate code if not already set
            if (empty($payment->payment_code)) {
                $payment->payment_code = self::generateUniqueCode();
            }
        });
    }

    /**
     * Generate a unique payment code.
     *
     * @return string
     */
    public static function generateUniqueCode()
    {
        // Format: PY-{CURRENT_YEAR}{RANDOM_STRING}
        $prefix = 'PY-' . date('Y');
        $uniqueString = strtoupper(Str::random(6));
        $paymentCode = $prefix . $uniqueString;

        // Check if code already exists, regenerate if needed
        while (self::where('payment_code', $paymentCode)->exists()) {
            $uniqueString = strtoupper(Str::random(6));
            $paymentCode = $prefix . $uniqueString;
        }

        return $paymentCode;
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

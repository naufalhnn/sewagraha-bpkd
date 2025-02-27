<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Str;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        if (empty($booking->booking_code)) {
            $booking->booking_code = $this->generateBookingCode();
        }
    }

    protected function generateBookingCode(): string
    {
        $prefix = 'BK';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        $code = "{$prefix}{$date}{$random}";

        // Pastikan kode unik
        while (Booking::where('booking_code', $code)->exists()) {
            $random = strtoupper(Str::random(4));
            $code = "{$prefix}{$date}{$random}";
        }

        return $code;
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        if (empty($payment->payment_code)) {
            $payment->payment_code = $this->generatePaymentCode();
        }
    }

    protected function generatePaymentCode(): string
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        $code = "{$prefix}-{$date}-{$random}";

        // Pastikan kode unik
        while (Payment::where('payment_code', $code)->exists()) {
            $random = strtoupper(Str::random(4));
            $code = "{$prefix}-{$date}-{$random}";
        }

        return $code;
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}

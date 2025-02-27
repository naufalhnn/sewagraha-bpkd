<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected static bool $canCreateAnother = false;



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['booking_code'] = BookingResource::generateBookingCode();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.bookings.upload-payment-proof', ['record' => $this->record->id]);
    }

    protected function afterCreate(): void
    {
        $booking = $this->record;

        $payment = $booking->payments()->create([
            'user_id' => $booking->user_id,
            'payment_code' => 'PAY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'total_price' => $booking->total_price,
            'status' => 'PENDING',
        ]);

        Notification::make()
            ->title('Booking created successfully!')
            ->body('Please proceed with the payment.')
            ->success()
            ->send();

        $this->redirect(PaymentResource::getUrl('edit', ['record' => $payment->id]));
    }
}

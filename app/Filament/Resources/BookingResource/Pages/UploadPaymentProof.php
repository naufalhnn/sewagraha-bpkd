<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Payment;
use App\Models\PaymentProof;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class UploadPaymentProof extends Page
{
    use InteractsWithForms;

    protected static string $resource = BookingResource::class;

    protected static string $view = 'filament.resources.booking-resource.pages.upload-payment-proof';

    public ?array $data = [];

    public $booking;
    public $payment;

    public function mount($record)
    {
        $this->booking = static::getResource()::getModel()::findOrFail($record);

        // Create payment record if not exists
        $this->payment = Payment::firstOrCreate(
            ['booking_id' => $this->booking->id],
            [
                'user_id' => $this->booking->user_id,
                'payment_code' => $this->generatePaymentCode(),
                'total_price' => $this->booking->total_price,
                'status' => 'PENDING'
            ]
        );

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Pembayaran')
                    ->schema([
                        Placeholder::make('booking_code')
                            ->label('Kode Booking')
                            ->content(fn() => $this->booking->booking_code),

                        Placeholder::make('venue_name')
                            ->label('Gedung')
                            ->content(fn() => $this->booking->venue->name),

                        Placeholder::make('event_dates')
                            ->label('Tanggal Acara')
                            ->content(function () {
                                $startDate = \Carbon\Carbon::parse($this->booking->event_start_date)->format('d F Y');
                                $endDate = \Carbon\Carbon::parse($this->booking->event_end_date)->format('d F Y');
                                return $startDate . ' - ' . $endDate;
                            }),

                        Placeholder::make('total_price')
                            ->label('Total Pembayaran')
                            ->content(fn() => 'Rp. ' . number_format($this->booking->total_price, 0, ',', '.')),

                        Section::make('Informasi Rekening Tujuan')
                            ->schema([
                                Placeholder::make('bank_name')
                                    ->label('Nama Bank')
                                    ->content('Bank BRI'),

                                Placeholder::make('account_number')
                                    ->label('Nomor Rekening')
                                    ->content('1234-5678-9012-3456'),

                                Placeholder::make('account_name')
                                    ->label('Nama Pemilik')
                                    ->content('BPKD Kabupaten Pekalongan'),

                                Placeholder::make('payment_instructions')
                                    ->label('Petunjuk Pembayaran')
                                    ->content('Silakan transfer sesuai jumlah yang tertera di atas. Setelah transfer, unggah bukti pembayaran Anda menggunakan form di bawah ini.')
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ]),

                Section::make('Upload Bukti Pembayaran')
                    ->schema([
                        FileUpload::make('payment_proof')
                            ->label('Bukti Transfer')
                            ->required()
                            ->image()
                            ->directory('payment-proofs')
                    ])
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        // Debug untuk melihat isi data
        // dd($data);

        // Save payment proof
        if (!empty($data['payment_proof'])) {
            // Periksa apakah payment_proof adalah array atau string
            $proofImagePath = is_array($data['payment_proof'])
                ? $data['payment_proof'][0]
                : $data['payment_proof'];

            PaymentProof::create([
                'payment_id' => $this->payment->id,
                'proof_image_path' => $proofImagePath,
                'uploaded_at' => now(),
            ]);

            // Update payment status
            $this->payment->update([
                'status' => 'PAID',
                'paid_at' => now(),
            ]);

            // Update booking status if needed
            if ($this->booking->status === 'PENDING') {
                $this->booking->update(['status' => 'PENDING']);
            }

            // Redirect to bookings list with success message
            return redirect()->route('filament.admin.resources.bookings.index')
                ->with('success', 'Bukti pembayaran berhasil diunggah');
        }

        // Jika tidak ada file yang diupload, kembalikan dengan pesan error
        $this->addError('payment_proof', 'Bukti pembayaran wajib diupload');
        return null;
    }

    private function generatePaymentCode(): string
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        $code = "{$prefix}{$date}{$random}";

        // Ensure code is unique
        while (Payment::where('payment_code', $code)->exists()) {
            $random = strtoupper(Str::random(4));
            $code = "{$prefix}{$date}{$random}";
        }

        return $code;
    }
}

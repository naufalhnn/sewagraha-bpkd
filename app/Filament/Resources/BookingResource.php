<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use App\Models\Venue;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Reservasi';

    protected static ?string $title = 'Reservasi';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'PENDING')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pemesan')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm(function (Form $form) {
                        return $form->schema([
                            TextInput::make('name')->label('Nama')->required()->maxLength(255),
                            TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
                            TextInput::make('phone')->label('Nomor Telepon')->required()->maxLength(16),
                            TextInput::make('password')->label('Kata Sandi')->required()->default('12345678'),
                        ]);
                    }),
                Select::make('venue_id')
                    ->relationship('venue', 'name')
                    ->label('Gedung')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $venue = \App\Models\Venue::find($state);
                            if ($venue) {
                                $set('base_price_display', $venue->base_price);
                                $set('base_price', $venue->base_price);
                            }
                        } else {
                            $set('base_price_display', null);
                            $set('base_price', null);
                        }
                    }),
                TextInput::make('purpose')->label('Keperluan')->required()->maxLength(255),
                DatePicker::make('event_start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(now())
                    ->timezone('Asia/Jakarta')
                    ->native(false)
                    ->displayFormat('d F Y')
                    ->reactive(),
                DatePicker::make('event_end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->default(now())
                    ->timezone('Asia/Jakarta')
                    ->native(false)
                    ->displayFormat('d F Y')
                    ->reactive(),
                TextInput::make('booking_code')
                    ->label('Kode Booking (Auto Generate)')
                    ->default(fn() => BookingResource::generateBookingCode())
                    ->disabled()
                    ->dehydrated(true)
                    ->visibleOn('create'),
                TextInput::make('booking_code')
                    ->label('Kode Booking')
                    ->disabled()
                    ->visibleOn('edit'),

                Placeholder::make('total_price_display')
                    ->label('Total Harga')
                    ->content(function (callable $get) {
                        $venueId = $get('venue_id');
                        $startDate = $get('event_start_date');
                        $endDate = $get('event_end_date');

                        if (!$venueId || !$startDate || !$endDate) {
                            return '-';
                        }

                        $venue = Venue::find($venueId);
                        if (!$venue) {
                            return '-';
                        }

                        $startDate = Carbon::parse($startDate);
                        $endDate = Carbon::parse($endDate);

                        $daysDifference = $startDate->isSameDay($endDate) ? 1 : $startDate->diffInDays($endDate);

                        $totalPrice = $venue->base_price * $daysDifference;

                        return "Rp. " . number_format($totalPrice, 0, ',', '.');
                    }),

                // Add this inside your BookingResource form() method, replace the Hidden total_price field
                Hidden::make('total_price')
                    ->dehydrated(true)
                    ->afterStateHydrated(function (callable $set, callable $get) {
                        $venueId = $get('venue_id');
                        $startDate = $get('event_start_date');
                        $endDate = $get('event_end_date');

                        if (!$venueId || !$startDate || !$endDate) {
                            return;
                        }

                        $venue = Venue::find($venueId);
                        if (!$venue) {
                            return;
                        }

                        $startDate = Carbon::parse($startDate);
                        $endDate = Carbon::parse($endDate);

                        $daysDifference = $startDate->isSameDay($endDate) ? 1 : $startDate->diffInDays($endDate) + 1;
                        $totalPrice = $venue->base_price * $daysDifference;

                        $set('total_price', $totalPrice);
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        // Same calculation as above
                        $venueId = $get('venue_id');
                        $startDate = $get('event_start_date');
                        $endDate = $get('event_end_date');

                        if (!$venueId || !$startDate || !$endDate) {
                            return;
                        }

                        $venue = Venue::find($venueId);
                        if (!$venue) {
                            return;
                        }

                        $startDate = Carbon::parse($startDate);
                        $endDate = Carbon::parse($endDate);

                        $daysDifference = $startDate->isSameDay($endDate) ? 1 : $startDate->diffInDays($endDate) + 1;
                        $totalPrice = $venue->base_price * $daysDifference;

                        $set('total_price', $totalPrice);
                    }),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')->label('Kode Pemesanan'),
                Tables\Columns\TextColumn::make('venue.name')->label('Gedung'),
                Tables\Columns\TextColumn::make('user.name')->label('Pemesan'),
                Tables\Columns\TextColumn::make('purpose')->label('Keperluan'),
                Tables\Columns\TextColumn::make('event_start_date')->label('Tanggal Mulai')->date('d F Y'),
                Tables\Columns\TextColumn::make('event_end_date')->label('Tanggal Selesai')->date('d F Y'),
                Tables\Columns\TextColumn::make('total_price')->label('Total Harga')->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'PENDING' => 'Menunggu Konfirmasi',
                        'CONFIRMED' => 'Dikonfirmasi',
                        'COMPLETED' => 'Selesai',
                        'CANCELED' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'CONFIRMED' => 'success',
                        'COMPLETED' => 'info',
                        'CANCELED' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
            'upload-payment-proof' => Pages\UploadPaymentProof::route('/{record}/upload-payment-proof'),
        ];
    }

    public static function generateBookingCode(): string
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

    protected function calculateTotalAmount($venueId, $startDate, $endDate, callable $set)
    {
        if (!$venueId || !$startDate || !$endDate) {
            $set('total_price', null);
            return;
        }

        $venue = \App\Models\Venue::find($venueId);
        if (!$venue) {
            $set('total_price', null);
            return;
        }

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $daysDifference = $startDate->isSameDay($endDate) ? 1 : $startDate->diffInDays($endDate);
        $totalPrice = $venue->base_price * $daysDifference;

        $set('total_price', $totalPrice);
    }
}

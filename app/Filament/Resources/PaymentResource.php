<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('payment_code')
                            ->label('Payment Code')
                            ->disabled(),

                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Amount To Pay')
                            ->formatStateUsing(fn($state) => 'Rp. ' . number_format($state, 0, ',', '.'))
                            ->disabled(),

                        Forms\Components\FileUpload::make('proof_image')
                            ->label('Upload Proof of Payment')
                            ->directory('payment-proofs')
                            ->required()
                            ->image()
                            ->visibility('public')
                            ->afterStateUpdated(function (callable $set, $state, $record) {
                                // This is only needed when saving the proof image path
                                // We'll handle this in the afterSave hook
                            }),

                    ])
                    ->columns(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_code')
                    ->label('Payment Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('booking.booking_code')
                    ->label('Booking Code'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'FAILED',
                        'warning' => 'PENDING',
                        'success' => 'PAID',
                        'secondary' => 'REFUNDED',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])

            ->filters([
                //
            ])
            ->actions([
                //
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

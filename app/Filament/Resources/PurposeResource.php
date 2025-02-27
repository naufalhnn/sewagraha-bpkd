<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurposeResource\Pages;
use App\Filament\Resources\PurposeResource\RelationManagers;
use App\Models\Purpose;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurposeResource extends Resource
{
    protected static ?string $model = Purpose::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Keperluan';

    protected static ?string $title = 'Keperluan';

    protected static ?string $breadcrumb = 'Keperluan';

    protected ?string $heading = 'Keperluan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Keperluan')->required()->maxLength(255),
                Textarea::make('description')->label('Deskripsi')->rows(3)->cols(20)->maxLength(255),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Keperluan'),
                Tables\Columns\TextColumn::make('description')->label('Deskripsi'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPurposes::route('/'),
            'create' => Pages\CreatePurpose::route('/create'),
            'edit' => Pages\EditPurpose::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Filament\Resources\VenueResource\RelationManagers;
use App\Models\Venue;
use App\Models\VenueImage;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class VenueResource extends Resource
{
  protected static ?string $model = Venue::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('name')->label('Nama Gedung')->required()->maxLength(255),
        Textarea::make('description')->label('Deskripsi')->rows(3)->cols(20)->maxLength(255),
        TextInput::make('address')->label('Alamat Gedung')->required()->maxLength(255),
        TextInput::make('capacity')->label('Kapasitas')->numeric()->required()->maxLength(10),
        Select::make('purpose_id')->relationship('purposes', 'name')->label('Keperluan')->required()->searchable()->preload()->multiple()->createOptionForm(function (Form $form) {
          return $form->schema([
            TextInput::make('name')->label('Nama Keperluan')->required()->maxLength(255),
            Textarea::make('description')->label('Deskripsi')->rows(3)->cols(20)->maxLength(255),
          ]);
        }),
        TextInput::make('base_price')->label('Harga Dasar')->numeric()->required()->maxLength(25)->prefix('Rp. '),
        Select::make('building_condition')->options([
          'Sangat Terawat' => 'SANGAT TERAWAT',
          'Terawat' => 'TERAWAT',
          'Cukup Terawat' => 'CUKUP TERAWAT',
          'Kurang Terawat' => 'KURANG TERAWAT',
          'Butuh Renovasi' => 'BUTUH RENOVASI',
        ])->label('Kondisi Gedung')->required(),
        Select::make('facility_id')->relationship('facilities', 'name')->label('Fasilitas')->multiple()->required()->preload()->createOptionForm(function (Form $form) {
          return $form->schema([
            TextInput::make('name')->label('Nama Fasilitas')->required()->maxLength(255),
            Textarea::make('description')->label('Deskripsi')->rows(3)->cols(20)->maxLength(255),
          ]);
        }),
        Forms\Components\Section::make('Gambar Gedung')
          ->schema([
            Forms\Components\FileUpload::make('venue_images')
              ->image()
              ->multiple()
              ->directory('venue-images')
              ->disk('public')
              ->visibility('public')
              ->columnSpanFull()
              // ->afterStateUpdated(function (Venue $record, $state) {
              //   if (! $record->exists) {
              //     return;
              //   }

              //   // Hapus gambar yang tidak ada lagi dalam state
              //   $existingImagePaths = $record->venueImages->pluck('image_path')->toArray();
              //   $newImagePaths = $state ?? [];

              //   $imagesToDelete = array_diff($existingImagePaths, $newImagePaths);
              //   foreach ($imagesToDelete as $imagePath) {
              //     $venueImage = $record->venueImages()->where('image_path', $imagePath)->first();
              //     if ($venueImage) {
              //       Storage::disk('public')->delete($imagePath);
              //       $venueImage->delete();
              //     }
              //   }
              // })
              ->saveRelationshipsUsing(function (Venue $record, $state) {
                if (! $state) {
                  return;
                }

                $existingImagePaths = $record->venueImages->pluck('image_path')->toArray();

                foreach ($state as $imagePath) {
                  if (!in_array($imagePath, $existingImagePaths)) {
                    $record->venueImages()->create([
                      'image_path' => $imagePath
                    ]);
                  }
                }
              })
          ]),
      ])->columns(1);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')->label('Nama Gedung'),
        Tables\Columns\TextColumn::make('description')->label('Deskripsi'),
        Tables\Columns\TextColumn::make('address')->label('Alamat Gedung'),
        Tables\Columns\TextColumn::make('capacity')->label('Kapasitas'),
        Tables\Columns\TextColumn::make('purposes.name')->label('Keperluan'),
        Tables\Columns\TextColumn::make('base_price')->label('Harga Dasar')->money('IDR')->suffix(' per hari'),
        Tables\Columns\TextColumn::make('building_condition')->label('Kondisi Gedung'),
        Tables\Columns\TextColumn::make('facilities.name')->label('Fasilitas'),
        Tables\Columns\ImageColumn::make('venueImages.image_path')
          ->label('Gambar')
          ->stacked()
          ->size(50)
          ->limit(3)
          ->getStateUsing(
            fn($record) =>
            $record->venueImages->pluck('image_path')->map(fn($path) => asset('storage/' . $path))->toArray()
          ),
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
      RelationManagers\VenueImagesRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListVenues::route('/'),
      'create' => Pages\CreateVenue::route('/create'),
      'edit' => Pages\EditVenue::route('/{record}/edit'),
    ];
  }
}

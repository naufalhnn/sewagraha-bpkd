<?php

namespace App\Filament\Resources\VenueResource\Pages;

use App\Filament\Resources\VenueResource;
use App\Models\VenueImage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVenue extends CreateRecord
{
    protected static string $resource = VenueResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

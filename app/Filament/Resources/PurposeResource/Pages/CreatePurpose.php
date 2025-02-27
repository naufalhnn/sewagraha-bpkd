<?php

namespace App\Filament\Resources\PurposeResource\Pages;

use App\Filament\Resources\PurposeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePurpose extends CreateRecord
{
    protected static string $resource = PurposeResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected static ?string $title = 'Tambah Keperluan';

    protected static ?string $breadcrumb = 'Tambah Keperluan';

    protected ?string $heading = 'Tambah Keperluan';
}

<?php

namespace App\Filament\Resources\UploadResource\Pages;

use App\Filament\Resources\UploadResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUpload extends ViewRecord
{
    protected static string $resource = UploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
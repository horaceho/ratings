<?php

namespace App\Filament\Resources\UploadResource\Pages;

use App\Models\Upload;
use App\Filament\Resources\UploadResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewUpload extends ViewRecord
{
    protected static string $resource = UploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Import')
                ->requiresConfirmation()
                ->icon('heroicon-o-arrow-up-tray')
                ->action(fn (Upload $upload) => $upload->doImport())
                ->after(fn () => Notification::make()
                    ->title('Import successfully')
                    ->success()
                    ->send()),
            Actions\EditAction::make(),
        ];
    }
}

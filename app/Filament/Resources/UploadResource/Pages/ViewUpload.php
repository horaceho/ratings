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
                ->modalHeading('Import Data')
                ->modalDescription('Confirm to import data of this Upload?')
                ->modalSubmitActionLabel('Yes, import them!')
                ->icon('heroicon-o-arrow-up-tray')
                ->action(fn (Upload $upload) => $upload->doImport())
                ->after(fn () => Notification::make()
                    ->title('Import successfully')
                    ->success()
                    ->send()),
            Actions\ActionGroup::make([
                Actions\Action::make('Delete Records')
                ->requiresConfirmation()
                ->modalHeading('Delete Records')
                ->modalDescription('Confirm to delete game records of this Upload?')
                ->modalSubmitActionLabel('Yes, delete them!')
                ->action(fn (Upload $upload) => $upload->doDeleteRecords())
                ->after(fn () => Notification::make()
                    ->title('Delete successfully')
                    ->success()
                    ->send()),
                Actions\Action::make('Delete Players')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Players')
                    ->modalDescription('Confirm to delete players of this Upload?')
                    ->modalSubmitActionLabel('Yes, delete them!')
                    ->action(fn (Upload $upload) => $upload->doDeletePlayers())
                    ->after(fn () => Notification::make()
                        ->title('Delete successfully')
                        ->success()
                        ->send()),
            ])->label('Delete')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->button(),
            Actions\EditAction::make(),
        ];
    }
}

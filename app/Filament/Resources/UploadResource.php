<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UploadResource\Pages;
use App\Filament\Resources\UploadResource\RelationManagers;
use App\Models\Upload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UploadResource extends Resource
{
    protected static ?string $model = Upload::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->required(),
                Forms\Components\FileUpload::make('filename')
                    ->required()
                    ->columnSpanFull()
                    ->disk('uploads')
                    ->storeFileNamesIn('original')
                    ->downloadable()
                    ->previewable(false),

                Forms\Components\Placeholder::make('Players')
                    // ->hintIcon('heroicon-o-user')
                    ->content(fn (Upload $upload): ?string => $upload?->players()->count()),
                Forms\Components\Placeholder::make('Records')
                    // ->hintIcon('heroicon-o-table-cells')
                    ->content(fn (Upload $upload): ?string => $upload?->records()->count()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original')
                    ->searchable(),
                Tables\Columns\TextColumn::make('players_count')
                    ->label('Players')
                    ->counts('players'),
                Tables\Columns\TextColumn::make('records_count')
                    ->label('Records')
                    ->counts('records'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUploads::route('/'),
            'create' => Pages\CreateUpload::route('/create'),
            'view' => Pages\ViewUpload::route('/{record}'),
            'edit' => Pages\EditUpload::route('/{record}/edit'),
        ];
    }
}

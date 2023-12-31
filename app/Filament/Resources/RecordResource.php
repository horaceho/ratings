<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecordResource\Pages;
use App\Filament\Resources\RecordResource\RelationManagers;
use App\Models\Record;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->native(false)
                    ->format('Y-m-d')
                    ->displayFormat('Y-m-d')
                    ->required(),
                Forms\Components\TextInput::make('team'),
                Forms\Components\TextInput::make('black')
                    ->required(),
                Forms\Components\TextInput::make('white')
                    ->required(),
                Forms\Components\TextInput::make('winner')
                    ->prefixIcon('heroicon-o-trophy'),
                Forms\Components\TextInput::make('result'),
                Forms\Components\TextInput::make('handicap')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('organization')
                    ->required(),
                Forms\Components\TextInput::make('match')
                    ->required(),
                Forms\Components\TextInput::make('group'),
                Forms\Components\TextInput::make('round')
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->prefixIcon('heroicon-o-link')
                    ->url(),
                Forms\Components\TextInput::make('remark')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('short')
                    ->label('')
                    ->prefixIcon('heroicon-o-tag')
                    ->disabled(),

                // Forms\Components\Placeholder::make('')
                //     ->content(fn (Record $record): ?string => $record?->short)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date($format = 'Y-m-d')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('team')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('black')
                    // ->label('⚫')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('white')
                    // ->label('⚪')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('winner')
                    ->label('🏆')
                    ->color('success')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('match')
                    ->label('')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->label('')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('round')
                    ->label('')
                    ->copyable()
                    ->searchable(),
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
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'view' => Pages\ViewRecord::route('/{record}'),
            'edit' => Pages\EditRecord::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
}

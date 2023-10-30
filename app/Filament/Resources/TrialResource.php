<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrialResource\Pages;
use App\Filament\Resources\TrialResource\RelationManagers;
use App\Models\Trial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrialResource extends Resource
{
    protected static ?string $model = Trial::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('algorithm')
                    ->options(config('ratings.algorithms.options'))
                    ->default(config('ratings.algorithms.default'))
                    ->required(),
                Forms\Components\TextInput::make('organization')
                    ->hint('WIP'),
                Forms\Components\TextInput::make('match')
                    ->hint('WIP'),
                Forms\Components\TextInput::make('group')
                    ->hint('WIP'),
                Forms\Components\DatePicker::make('from')
                    ->default(now()->startOfYear()->subYears(10))
                    ->native(false)
                    ->format('Y-m-d')
                    ->displayFormat('Y-m-d')
                    ->required(),
                Forms\Components\DatePicker::make('till')
                    ->default(now()->endOfYear())
                    ->native(false)
                    ->format('Y-m-d')
                    ->displayFormat('Y-m-d')
                    ->required(),
                Forms\Components\TextInput::make('rank_hi')
                    ->default('7d')
                    ->hint('Highest rank (default 7d)')
                    ->in(collect(config('ratings.ranks'))->keys()->toArray())
                    ->required(),
                Forms\Components\TextInput::make('rank_lo')
                    ->default('20k')
                    ->hint('Lowest rank (default 20k)')
                    ->in(collect(config('ratings.ranks'))->keys()->toArray())
                    ->required(),
                Forms\Components\TextInput::make('handicap')
                    ->default(0)
                    ->hint('Maximum handicap (default 0)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remark'),
                Forms\Components\KeyValue::make('meta')
                    ->hint('Algorithm parameters')
                    ->addActionLabel('')
                    ->addAction(
                        fn (Forms\Components\Actions\Action $action) => $action->icon('heroicon-o-plus'),
                    ),
                Forms\Components\Select::make('slot')
                    ->hint('Which player slot to save update GoR')
                    ->options(config('ratings.players.slot.options'))
                    ->default(config('ratings.players.slot.default'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('algorithm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('match')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('from')
                    ->date($format = 'Y-m-d'),
                Tables\Columns\TextColumn::make('till')
                    ->date($format = 'Y-m-d'),
                Tables\Columns\TextColumn::make('handicap')
                    ->numeric(),
                Tables\Columns\TextColumn::make('rank_lo'),
                Tables\Columns\TextColumn::make('rank_hi'),
                Tables\Columns\TextColumn::make('slot'),
                Tables\Columns\TextColumn::make('remark')
                    ->limit(50),
                Tables\Columns\TextColumn::make('results_count')
                    ->label('Results')
                    ->counts('results'),
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
            RelationManagers\ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrials::route('/'),
            'create' => Pages\CreateTrial::route('/create'),
            'view' => Pages\ViewTrial::route('/{record}'),
            'edit' => Pages\EditTrial::route('/{record}/edit'),
        ];
    }
}

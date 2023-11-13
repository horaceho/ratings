<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlayerResource\Pages;
use App\Filament\Resources\PlayerResource\RelationManagers;
use App\Models\Player;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('code'),
                Forms\Components\TextInput::make('alias'),
                Forms\Components\TextInput::make('other'),
                Forms\Components\TextInput::make('init'),
                Forms\Components\TextInput::make('gor'),
                Forms\Components\TextInput::make('s0'),
                Forms\Components\TextInput::make('s1'),
                Forms\Components\TextInput::make('s2'),
                Forms\Components\TextInput::make('s3'),
                Forms\Components\TextInput::make('s4'),
                Forms\Components\TextInput::make('s5'),
                Forms\Components\TextInput::make('s6'),
                Forms\Components\TextInput::make('s7'),
                Forms\Components\TextInput::make('s8'),
                Forms\Components\TextInput::make('s9'),
                Forms\Components\TextInput::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alias')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('other')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('init')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gor')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->gor, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable(),
                Tables\Columns\TextColumn::make('s0')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s0, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable(),
                Tables\Columns\TextColumn::make('s1')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s1, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable(),
                Tables\Columns\TextColumn::make('s2')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s2, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable(),
                Tables\Columns\TextColumn::make('s3')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s3, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('s4')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s4, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('s5')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s5, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('s6')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s6, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('s7')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s7, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('s8')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s8, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('s9')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->tooltip(function (Model $record): float {
                        return round($record->s9, 3);
                    })
                    ->alignment(Alignment::End)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remark')
                    ->searchable(),
            ])
            ->defaultSort('gor', 'desc')
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
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'view' => Pages\ViewPlayer::route('/{record}'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
}

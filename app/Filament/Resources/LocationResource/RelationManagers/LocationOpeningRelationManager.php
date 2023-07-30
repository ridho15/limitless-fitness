<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationOpeningRelationManager extends RelationManager
{
    protected static string $relationship = 'LocationOpening';

    protected static ?string $recordTitleAttribute = 'id_location';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('hari')
                    ->searchable()
                    ->options([
                        '1' => 'Monday',
                        '2' => 'Tuesday',
                        '3' => 'Wednesday',
                        '4' => 'Thursday',
                        '5' => 'Friday',
                        '6' => 'Saturday',
                        '7' => 'Sunday',
                    ])->required(),
                TimePicker::make('jam_open')->required(),
                TimePicker::make('jam_close')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hari')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 1:
                                return 'Monday';
                                break;
                            case 2:
                                return 'Tuesday';
                                break;
                            case 3:
                                return 'Wednesday';
                                break;
                            case 4:
                                return 'Thurday';
                                break;
                            case 5:
                                return 'Friday';
                                break;
                            case 6:
                                return 'Saturday';
                                break;
                            case 7:
                                return 'Sunday';
                                break;
                            default:
                                'not know';
                                break;
                        }
                    }),
                TextColumn::make('jam_open')->label('Opening')->sortable(),
                TextColumn::make('jam_close')->label('Close')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                ->label('Tambah Hari dan Jam')
                ->icon('heroicon-o-plus-circle')
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerJadwalStaticResource\Pages;
use App\Filament\Resources\CustomerJadwalStaticResource\RelationManagers;
use App\Models\CustomerJadwalStatic;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerJadwalStaticResource extends Resource
{
    protected static ?string $model = CustomerJadwalStatic::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Jadwal Statis Customer')
                            ->schema([
                                Select::make('id_user')
                                    ->label('Customer')
                                    ->preload()
                                    ->searchable()
                                    ->relationship('user', 'name')
                                    ->required(),
                                Select::make('hari')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->options(function () {
                                        return [
                                            0 => 'Senin',
                                            1 => 'Selasa',
                                            2 => 'Rabu',
                                            3 => 'Kamis',
                                            4 => 'Jumat',
                                            5 => 'Sabtu',
                                            6 => 'Minggu',
                                        ];
                                    }),
                                TimePicker::make('jam')
                                    ->label('Jam')
                                    ->required()
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('hari')
                    ->label('Hari')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case '0':
                                return 'Senin';
                                break;
                            case '1':
                                return 'Selasa';
                                break;
                            case '2':
                                return 'Rabu';
                                break;
                            case '3':
                                return 'Kamis';
                                break;
                            case '4':
                                return 'Jumat';
                                break;
                            case '5':
                                return 'Sabtu';
                                break;
                            case '6':
                                return 'Minggu';
                                break;
                            default:
                                break;
                        }
                    }),
                TextColumn::make('jam')
                    ->label('Jam')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCustomerJadwalStatics::route('/'),
            'create' => Pages\CreateCustomerJadwalStatic::route('/create'),
            'edit' => Pages\EditCustomerJadwalStatic::route('/{record}/edit'),
        ];
    }
}

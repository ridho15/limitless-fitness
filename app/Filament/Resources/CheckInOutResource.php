<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckInOutResource\Pages;
use App\Filament\Resources\CheckInOutResource\RelationManagers;
use App\Models\CheckInOut;
use App\Models\Location;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CheckInOutResource extends Resource
{
    protected static ?string $model = CheckInOut::class;

    protected static ?string $navigationIcon = 'heroicon-o-selector';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Check In Out')
                    ->schema([
                        Select::make('id_user')
                            ->label('Customer')
                            ->searchable()
                            ->options(User::whereHas('roles', function ($query) {
                            })->get()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get) {
                                $listOrderPaket = OrderPaket::where('id_user', $get('id_user'))
                                    ->where('status', 1)
                                    ->where('is_active', 1)
                                    ->whereDate('expired_date', '>=', now())
                                    ->get();
                                $data = [];
                                foreach ($listOrderPaket as $item) {
                                    $data[$item->id] = $item->paket->nama;
                                }
                                $set('list_order_paket', $data);
                            })
                            ->preload(),
                        Select::make('id_location')
                            ->searchable()
                            ->label('Location')
                            ->preload()
                            ->required()
                            ->options(Location::get()->pluck('nama', 'id')),
                        Select::make('id_order_paket')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->label('Order Paket')
                            ->options(function ($get) {
                                return $get('list_order_paket');
                            }),
                        DateTimePicker::make('jadwal')
                            ->label('Jadwal')
                            ->required(),
                        Radio::make('status')
                            ->inline()
                            ->label('Masuk/Keluar')
                            ->options([
                                '1' => "Masuk",
                                '2' => "Keluar"
                            ])
                            ->required()
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
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('location.nama')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('orderPaket.paket.nama')
                    ->label('Paket')
                    ->searchable(),
                TextColumn::make('jadwal')
                    ->label('Jadwal')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Masuk / Keluar')
                    ->color(function($state){
                        if($state == 1){
                            return 'success';
                        }else{
                            return 'secondary';
                        }
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 1) {
                            return 'Masuk';
                        } else {
                            return "Keluar";
                        }
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index' => Pages\ListCheckInOuts::route('/'),
            'create' => Pages\CreateCheckInOut::route('/create'),
            'edit' => Pages\EditCheckInOut::route('/{record}/edit'),
        ];
    }
}

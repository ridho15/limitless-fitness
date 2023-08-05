<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderPaketResource\Pages;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class OrderPaketResource extends Resource
{
    protected static ?string $model = OrderPaket::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Booking Order')
                            ->schema([
                                Select::make('id_user')
                                    ->searchable()
                                    ->preload()
                                    ->label('Customer')
                                    ->options(User::whereHas('roles', function ($query) {
                                        $query->where('name', 'Customer');
                                    })->pluck('name', 'id'))
                                    ->required(),
                                Select::make('id_paket')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->label('Pilih Paket')
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        $paket = Paket::find($state);
                                        if ($paket) {
                                            $set('harga', $paket->harga_diskon);
                                        }
                                    })
                                    ->relationship('paket', 'nama')
                                    ->required(),
                                TextInput::make('harga')
                                    ->numeric()
                                    ->required(),
                                Radio::make('status')
                                    ->options([
                                        '0' => 'Batal',
                                        '1' => 'Lunas',
                                        '2' => 'Belum Lunas'
                                    ])->required(),
                                DateTimePicker::make('expired_date')
                                    ->label('Tanggal Expired')
                                    ->required(),
                                DateTimePicker::make('tanggal_pembayaran')
                                    ->label('Tanggal Pembayaran')
                                    ->nullable(),
                                FileUpload::make('bukti_pembayaran')
                                    ->image()
                                    ->maxSize(2048),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('paket.nama')
                    ->label('Paket')
                    ->searchable(),
                TextColumn::make('harga_formatted')
                    ->sortable(),
                TextColumn::make('status_formatted')
                    ->label('Status'),
                BadgeColumn::make('is_active')
                    ->label('Status Aktif')
                    ->enum([
                        0 => 'Tidak aktif',
                        1 => 'Aktif'
                    ])
                    ->colors([
                        'danger' => 0,
                        'primary' => 1
                    ]),
                ImageColumn::make('bukti_pembayaran'),
                TextColumn::make('tanda_tangan'),
                TextColumn::make('expired_date')->sortable(),
                TextColumn::make('tanggal_pembayaran')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make()
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
            'index' => Pages\ListOrderPakets::route('/'),
            'create' => Pages\CreateOrderPaket::route('/create'),
            'edit' => Pages\EditOrderPaket::route('/{record}/edit'),
        ];
    }
}

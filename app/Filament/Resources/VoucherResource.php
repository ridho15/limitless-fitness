<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\Pages\ViewVoucher;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Filament\Resources\VoucherResource\RelationManagers\VoucherCustomerRelationManager;
use App\Models\Paket;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Voucher')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Voucher')
                            ->required()
                            ->string()
                            ->maxLength(255),
                        Select::make('id_paket')
                            ->label('Paket')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->options(Paket::pluck('nama', 'id'))
                            ->multiple()
                            ->helperText('Jika paket tidak di pilih. Voucher avaiable untuk semua paket'),
                        TextInput::make('disc_harga')
                            ->numeric()
                            ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                'uang' => fn (Mask $mask) => $mask->numeric()
                                    ->thousandsSeparator('.')
                                    ->decimalSeparator(',')
                            ])->pattern('Rp.uang'))
                            ->nullable(),
                        TextInput::make('disc_percent')
                            ->label('Discount Percent')
                            ->numeric()
                            ->maxValue(100)
                            ->nullable(),
                        TextInput::make('kode_voucher')
                            ->label('Kode Voucher')
                            ->maxLength(255)
                            ->required(),
                        DateTimePicker::make('tanggal_aktif')
                            ->label('Tanggal Aktif')
                            ->nullable(),
                        DateTimePicker::make('expired')
                            ->label('Expired Date')
                            ->required(),
                        TextInput::make('lama_aktif')
                            ->label('Lama Aktif')
                            ->helperText("Masukkan jumlah hari aktif"),
                        Textarea::make('deskripsi')
                            ->nullable(),
                        FileUpload::make('image')
                            ->nullable()
                            ->image()
                            ->maxSize(1024),
                        Radio::make('sekali_gunakan')
                            ->label('Tipe Voucher')
                            ->options([
                                0 => 'Bisa Berkali Kali',
                                1 => 'Sekali Gunakan'
                            ])->required(),
                        Radio::make('status')
                            ->options([
                                '1' => 'Aktif',
                                '0' => 'Tidak aktif'
                            ])->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Voucher')
                    ->searchable(),
                ImageColumn::make('image'),
                TextColumn::make('id_paket')
                    ->label('Paket')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        if ($record->id_paket != null) {
                            $id_paket = json_decode($record->id_paket);
                            $listPaket = Paket::whereIn('id', $id_paket)->select('nama')->get();
                            $text = "";
                            foreach ($listPaket as $item) {
                                $text .= $item->nama . ",";
                            }

                            return $text;
                        } else {
                            return 'Voucher avaiable semua paket';
                        }
                    }),
                TextColumn::make('expired')
                    ->label('Tanggal Kadaluarsa'),
                BadgeColumn::make('status_formatted')
                    ->label('Status Aktif')
                    ->colors([
                        '0' => 'secondary',
                        '1' => 'success'
                    ]),
                TextColumn::make('disc_harga')
                    ->formatStateUsing(function ($record) {
                        return 'Rp.' . number_format($record->disc_harga, 0, ',', '.');
                    })
                    ->label('Discount Harga'),
                TextColumn::make('disc_percent')
                    ->formatStateUsing(function ($record) {
                        return $record->disc_percent ?? '0' . '%';
                    })
                    ->label('Discount Percent'),
                TextColumn::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->searchable(),
                BadgeColumn::make('sekali_gunakan')
                    ->label('Tipe Voucher')
                    ->colors([
                        'primary' => 1,
                        'secondary' => 0
                    ])
                    ->enum([
                        1 => 'Sekali Gunakan',
                        0 => 'Bisa Berkali kali'
                    ]),
                TextColumn::make('tanggal_aktif')
                    ->label('Tanggal Aktif'),
                TextColumn::make('lama_aktif')
                    ->label('Lama Aktif'),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VoucherCustomerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'view' => ViewVoucher::route('/{record}'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}

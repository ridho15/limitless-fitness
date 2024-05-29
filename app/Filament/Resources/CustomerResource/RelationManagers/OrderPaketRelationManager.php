<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\Paket;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class OrderPaketRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderPaket';

    protected static ?string $recordTitleAttribute = 'id_user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_paket')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->label('Pilih Paket')
                    ->afterStateUpdated(function (Closure $set, $state) {
                        $paket = Paket::find($state);
                        if ($paket) {
                            $set('harga', $paket->harga_diskon);
                            $set('honor', $paket->honor);
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->sortable(),
                TextColumn::make('paket.nama')
                    ->label('Paket')
                    ->searchable(),
                TextColumn::make('sisa')
                    ->sortable(),
                TextColumn::make('harga_formatted')
                    ->label('Harga')
                    ->sortable(),
                TextColumn::make('status_formatted')
                    ->label('Status'),
                BadgeColumn::make('is_active')
                    ->label('Status Aktif')
                    ->enum([
                        '0' => 'Tidak aktif',
                        '1' => 'Aktif'
                    ])
                    ->colors([
                        'danger' => 0,
                        'primary' => 1
                    ]),
                ImageColumn::make('bukti_pembayaran')
                    ->size(100),
                TextColumn::make('expired_date')->sortable(),
                TextColumn::make('tanggal_pembayaran')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label("Tambah Order")
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function ($data) {
                        if ($data['status'] == 1) {
                            $data['is_active'] = 1;
                        } else {
                            $data['is_active'] = 0;
                        }

                        return $data;
                    })
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        return parent::getTableQuery()->orderBy('created_at', 'DESC');
    }
}

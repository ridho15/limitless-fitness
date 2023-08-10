<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderPaketResource\Pages;
use App\Models\Jurnal;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

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
                                            $set('jumlah_pertemuan', $paket->jumlah_pertemuan);
                                            $set('honor', $paket->honor);
                                        }
                                    })
                                    ->relationship('paket', 'nama')
                                    ->required(),
                                TextInput::make('jumlah_pertemuan')
                                    ->label('Jumlah Pertemuan')
                                    ->required()
                                    ->default(0),
                                TextInput::make('harga')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('honor')
                                    ->label('Pembayaran Instruktur')
                                    ->helperText('Pembayaran instruktur per order paket')
                                    ->numeric()
                                    ->default(0)
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
                TextColumn::make('jumlah_pertemuan')
                    ->sortable(),
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
                ImageColumn::make('bukti_pembayaran')
                    ->size(100),
                TextColumn::make('honor')
                    ->label('Pembayaran Instruktur')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('tanda_tangan'),
                TextColumn::make('expired_date')->sortable(),
                TextColumn::make('tanggal_pembayaran')->sortable(),
            ])
            ->filters([
                SelectFilter::make('id_user')
                    ->label('Customer')
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Customer');
                    })->get()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('id_paket')
                    ->label('Paket')
                    ->options(Paket::get()->pluck('nama', 'id'))
                    ->searchable(),
                Filter::make('created_at')
                    ->label('Tanggal Order')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['created_from'],
                            fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date),
                        )->when($data['created_until'], fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
                SelectFilter::make('is_active')->label('Status Aktif')->searchable()->options([
                    0 => 'Tidak Aktif',
                    1 => 'Aktif'
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()->successNotificationTitle('Berhasil menghapus order paket'),
                    Action::make('bukti_pembayaran')
                        ->icon('heroicon-o-photograph')
                        ->label('View Bukti Pembayaran')
                        ->url(function (OrderPaket $record) {
                            return asset('storage/' . $record->bukti_pembayaran);
                        })
                        ->openUrlInNewTab(),
                    Action::make('approve')
                        ->icon('heroicon-o-cash')
                        ->label('Approve Pembayaran')
                        ->action(function (OrderPaket $record) {
                            $jurnal = Jurnal::updateOrCreate([
                                'id_order_paket' => $record->id,
                                'tipe' => 1
                            ], [
                                'nama' => "Peserta an, " . $record->user->name . ' ' . $record->paket->nama,
                                'jumlah_uang' => $record->harga,
                                'tipe' => 1,
                                'struk' => $record->bukti_pembayaran,
                                'tanggal' => now()
                            ]);
                            Jurnal::updateOrCreate([
                                'id_order_paket' => $record->id,
                                'id_parent' => $jurnal->id,
                                'tipe' => 0,
                            ], [
                                'nama' => 'Biaya Instruktur 1px group @' . $record->honor,
                                'jumlah_uang' => $record->honor * $record->jumlah_pertemuan,
                                'tipe' => '0',
                                'struk' => null,
                                'tanggal' => now()
                            ]);
                            $record->update([
                                'status' => 1,
                                'is_active' => 1
                            ]);
                        })->requiresConfirmation(),
                    ViewAction::make(),
                ])
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

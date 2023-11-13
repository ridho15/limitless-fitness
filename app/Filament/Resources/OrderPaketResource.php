<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderPaketResource\Pages;
use App\Models\Jurnal;
use App\Models\Location;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Carbon\Carbon;
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
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                ViewColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => date('d-m-Y H:i', strtotime($record->created_at))
                        ]);
                    })
                    ->view('custom-column.order-paket')
                    ->sortable(),
                ViewColumn::make('user.name')
                    ->label('Customer')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->user->name
                        ]);
                    })
                    ->view('custom-column.order-paket')
                    ->searchable(),
                ViewColumn::make('paket.nama')
                    ->label('Paket')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->paket->nama
                        ]);
                    })
                    ->view('custom-column.order-paket')
                    ->searchable(),
                ViewColumn::make('jumlah_pertemuan')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->jumlah_pertemuan
                        ]);
                    })
                    ->view('custom-column.order-paket')
                    ->sortable(),
                ViewColumn::make('sisa')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->sisa
                        ]);
                    })
                    ->view('custom-column.order-paket')
                    ->sortable(),
                ViewColumn::make('harga_formatted')
                    ->view('custom-column.order-paket')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->harga_formatted
                        ]);
                    })
                    ->label('Harga')
                    ->sortable(),
                ViewColumn::make('status')
                    ->view('custom-column.order-paket')
                    ->getStateUsing(function ($record) {
                        if ($record->status == 1) {
                            $text = 'Lunas';
                        } elseif ($record->status == 2) {
                            $text = 'Belum Lunas';
                        } else {
                            $text = 'Batal';
                        }
                        return json_encode([
                            'status' => $record->status,
                            'data' => $text
                        ]);
                    })
                    ->sortable()
                    ->label('Status'),
                ViewColumn::make('is_active')
                    ->label('Status Aktif')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->is_active == 1 ? 'Aktif' : 'Tidak Aktif'
                        ]);
                    })
                    ->view('custom-column.order-paket')
                    ->sortable(),
                ViewColumn::make('paket.location')
                    ->label('Location')
                    ->getStateUsing(function ($record) {
                        $locationNama = 'All Location';
                        if ($record->paket->location != 0) {
                            $locationNama = $record->paket->locationRelation->nama;
                        }
                        return json_encode([
                            'status' => $record->status,
                            'data' => $locationNama
                        ]);
                    })->view('custom-column.order-paket'),
                ViewColumn::make('expired_date')->view('custom-column.order-paket')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->expired_date
                        ]);
                    })->sortable(),
                ViewColumn::make('tanggal_pembayaran')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->tanggal_pembayaran
                        ]);
                    })->view('custom-column.order-paket')->sortable(),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->label('Location')
                    ->options(function () {
                        $location = Location::get()->pluck('nama', 'id')->toArray();
                        $location[0] = 'All location';

                        return $location;
                    })
                    ->query(function (Builder $query, $data) {
                        return $query->when($data['value'], function (Builder $query, $location) {
                            return $query->whereHas('paket', function ($query) use ($location) {
                                $query->where('location', $location);
                            });
                        });
                    })
                    ->searchable()
                    ->hidden(function () {
                        if (Auth::user()->hasRole(['Admin'])) {
                            return true;
                        }
                        return false;
                    }),
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
                    DeleteAction::make()->successNotificationTitle('Berhasil menghapus order paket')
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('Menghapus data order paket');
                        }),
                    Action::make('bukti_pembayaran')
                        ->icon('heroicon-o-photograph')
                        ->label('View Bukti Pembayaran')
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('View bukti pembayaran');
                        })
                        ->url(function (OrderPaket $record) {
                            return asset('storage/' . $record->bukti_pembayaran);
                        })
                        ->openUrlInNewTab(),
                    Action::make('approve')
                        ->icon('heroicon-o-cash')
                        ->label('Approve Pembayaran')
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('Melakukan approve pembayaran order paket');
                        })
                        ->action(function (OrderPaket $record) {
                            $jurnal = Jurnal::updateOrCreate([
                                'id_order_paket' => $record->id,
                                'tipe' => 1
                            ], [
                                'nama' => "Peserta an, " . $record->user->name . ' ' . $record->paket->nama,
                                'jumlah_uang' => $record->harga,
                                'tipe' => 1,
                                'struk' => $record->bukti_pembayaran,
                                'tanggal' => now(),
                                'id_user' => Auth::id(),
                                'id_order_paket' => $record->id
                            ]);
                            Jurnal::updateOrCreate([
                                'id_order_paket' => $record->id,
                                'id_parent' => $jurnal->id,
                                'tipe' => 0,
                            ], [
                                'nama' => 'Biaya Instruktur 1px group @' . $record->paket->kelas->honor,
                                'jumlah_uang' => $record->paket->kelas->honor * $record->jumlah_pertemuan,
                                'tipe' => '0',
                                'struk' => null,
                                'tanggal' => now(),
                                'id_user' => Auth::id(),
                                'id_order_paket' => $record->id
                            ]);
                            if ($record->is_active != 1) {
                                $tanggalExpired = Carbon::now()->addDays($record->paket->valid_day);
                                $record->update([
                                    'status' => 1,
                                    'expired_date' => $tanggalExpired,
                                    'sisa' => $record->jumlah_pertemuan
                                ]);
                            }
                        })->requiresConfirmation(),
                    ViewAction::make(),
                ])
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(function ($query) {
            if (Auth::user()->hasRole('Admin')) {
                $query->whereHas('paket', function ($query) {
                    $query->where('location', Auth::user()->id_location);
                });
            }
        })->orderBy('status', 'DESC')->orderBy('created_at', 'DESC');
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\Pages\KreditCustomer;
use App\Filament\Resources\CustomerResource\Pages\ViewCustomer;
use App\Filament\Resources\CustomerResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\CheckInOutRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\KreditKelasRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\OrderPaketRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\VoucherCustomerRelationManager;
use App\Models\CheckInOut;
use App\Models\OrderPaket;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Data Customer';

    protected static ?string $slug = 'customer';

    protected static ?string $navigationGroup = 'User';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        $roles = Role::where('name', 'customer')->first();
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Customer')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->string()
                                    ->required(),
                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->numeric()
                                    ->length(16)
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                                TextInput::make('no_hp')
                                    ->label('Nomor Handphone')
                                    ->tel()
                                    ->telRegex('/^(\+62|0)(\d{8,12})$/')
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->nullable(),
                                TextInput::make('alamat')
                                    ->required()
                                    ->string()
                                    ->maxLength(255),
                                DateTimePicker::make('email_verified_at'),
                                TextInput::make('password')
                                    ->minLength(8)
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->password()
                                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                    ->dehydrated(fn($state) => filled($state)),
                                Select::make('id_location')
                                    ->label('Location')
                                    ->preload()
                                    ->searchable()
                                    ->relationship('location', 'nama')
                                    ->nullable(),
                                FileUpload::make('ktp')
                                    ->label('KTP')
                                    ->image()
                                    ->maxSize(2048)
                                    ->nullable(),
                                FileUpload::make('selfie')
                                    ->label('Selfie')
                                    ->image()
                                    ->maxSize(2048)
                                    ->nullable(),
                                FileUpload::make('foto')
                                    ->label('Foto')
                                    ->image()
                                    ->maxSize(2048)
                                    ->nullable(),
                                Grid::make()
                                    ->schema([
                                        Select::make('roles')
                                            ->label('Roles')
                                            ->multiple()
                                            ->default($roles ? [$roles->id] : null)
                                            ->preload()
                                            ->relationship('roles', 'name')
                                            ->disabled()
                                            ->required(),
                                        Toggle::make('status')
                                            ->label('Status User'),
                                        Radio::make('jenis_kelamin')
                                            ->label('Jenis Kelamin')
                                            ->options([
                                                '1' => 'Laki-Laki',
                                                '0' => 'Perempuan'
                                            ])->required()
                                    ])->columns(3),
                                FileUpload::make('ktp')
                                    ->label('KTP')
                                    ->image()
                                    ->maxSize(1024),
                                FileUpload::make('selfie')
                                    ->label('Foto Selfie')
                                    ->image()
                                    ->maxSize(1024)
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ViewColumn::make('barcode')
                    ->label('Barcode')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->barcode
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->name
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->nik
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->email
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('status')
                    ->label('Status User')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->status == 1 ? 'Aktif' : 'Belum / Tidak Aktif'
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->tanggal_lahir
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('no_hp')
                    ->label('Nomor Handphone')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->no_hp
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('alamat')
                    ->label('Alamat')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->alamat
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('location.name')
                    ->label('Location')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->location->nama
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->tanggal_lahir
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('email_verified_at')
                    ->label('Tanggal Verifikasi Email')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->email_verified_at
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->created_at
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
                ViewColumn::make('updated_at')
                    ->label('Tanggal Update')
                    ->getStateUsing(function ($record) {
                        return json_encode([
                            'status' => $record->status,
                            'data' => $record->updated_at
                        ]);
                    })
                    ->view('custom-column.customer')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->nullable(),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->nullable()
                    ])->query(function (Builder $query, $data) {
                        return $query->when($data['start_date'], function (Builder $query, $date) {
                            return $query->whereDate('created_at', '>=', $date);
                        })->when($data['end_date'], function (Builder $query, $date) {
                            return $query->where('created_at', '<=', $date);
                        });
                    }),
                SelectFilter::make('id_location')
                    ->label('Location')
                    ->hidden(function () {
                        if (Auth::user()->hasRole(['Admin'])) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->searchable()
                    ->relationship('location', 'nama'),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('Menghapus data customer');
                        }),
                    ViewAction::make(),
                    Action::make('konfirmasi')
                        ->icon(function ($record) {
                            if ($record->status == 1) {
                                return 'heroicon-o-ban';
                            } else {
                                return 'heroicon-o-check-circle';
                            }
                        })
                        ->label(function ($record) {
                            if ($record->status == 1) {
                                return "Non Aktfikan";
                            } else {
                                return "Aktifkan";
                            }
                        })->color(function ($record) {
                            if ($record->status == 1) {
                                return 'secondary';
                            } else {
                                return 'success';
                            }
                        })->action(function ($record) {
                            if ($record->status == 1) {
                                $record->status = 0;
                                $record->save();
                            } else {
                                $record->status = 1;
                                $record->save();
                            }
                            activity()->causedBy(Auth::user())
                                ->log('Melakukan update status aktifasi customer');
                        })->requiresConfirmation(),
                    Action::make('login')
                        ->icon('heroicon-o-arrow-circle-right')
                        ->label('Login')
                        ->action(function ($record) {
                            activity()->causedBy(Auth::user())
                                ->log('Melakukan login ke user ' . $record->name);

                            Auth::loginUsingId($record->id);
                            Filament::auth()->loginUsingId($record->id);
                            session()->regenerate();
                            return redirect()->route('base');
                        })
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CheckInOutRelationManager::class,
            OrderPaketRelationManager::class,
            VoucherCustomerRelationManager::class,
            // BookingKelasRelationManager::class,
            // KreditKelasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            // 'kredit-customer' => KreditCustomer::route('/kredit-customer/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->when(Auth::user()->hasRole(['Admin']), function ($query) {
            $query->where('id_location', Auth::user()->id_location);
        })->whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->orderBy('created_at', 'DESC');
    }
}

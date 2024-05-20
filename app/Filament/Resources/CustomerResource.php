<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\Pages\KreditCustomer;
use App\Filament\Resources\CustomerResource\Pages\ViewCustomer;
use App\Filament\Resources\CustomerResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\KreditKelasRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\OrderPaketRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\VoucherCustomerRelationManager;
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
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User';

    protected static ?string $navigationLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Data Customer';

    protected static ?string $slug = 'customer';

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
                                    ->string()
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
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state)),
                                Select::make('id_location')
                                    ->label('Location')
                                    ->preload()
                                    ->searchable()
                                    ->relationship('location', 'nama')
                                    ->nullable(),
                                FileUpload::make('foto')
                                    ->image()
                                    ->maxSize(2048)
                                    ->nullable(),
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
                ImageColumn::make('foto')
                    ->circular()
                    ->height(75)
                    ->toggleable()
                    ->width(75),
                TextColumn::make('name')
                    ->label('nama')
                    ->searchable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('Status User')
                    ->enum([
                        '1' => 'Aktif',
                        '0' => 'Belum / Tidak aktif'
                    ])->color(function($state){
                        if($state == 1){
                            return 'success';
                        }else{
                            return 'secondary';
                        }
                    }),
                TextColumn::make('tanggal_lahir')
                    ->toggleable()
                    ->label('Tanggal Lahir')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    })->searchable()
                    ->toggleable(),
                TextColumn::make('no_hp')
                    ->label('Nomor Hp')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable(),
                TextColumn::make('location.nama')
                    ->label('Location')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir'),
                TextColumn::make('email_verified_at')
                    ->label('Tanggal Verifikasi Email')
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),

                ImageColumn::make('ktp')
                    ->label('KTP')
                    ->url(function ($record) {
                        return asset('storage/' . $record->ktp);
                        // return redirect()->to(asset('storage/' . $record->ktp));
                    })
                    ->openUrlInNewTab()
                    ->size(75),
                ImageColumn::make('selfie')
                    ->label('Foto Selfie')
                    ->url(function ($record) {
                        return asset('storage/' . $record->selfie);
                        // return redirect()->to(asset('storage/' . $record->selfie));
                    })
                    ->openUrlInNewTab()
                    ->size(75),
                TextColumn::make('status_formatted')
                    ->toggleable()
                    ->label('Status User'),
                TextColumn::make('created_at')
                    ->label('Tanggal Buat')
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Tanggal Pembaharuan')
                    ->sortable()
                    ->toggleable()
                    ->dateTime()
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
                    })
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
            BookingKelasRelationManager::class,
            OrderPaketRelationManager::class,
            VoucherCustomerRelationManager::class,
            // KreditKelasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'kredit-customer' => KreditCustomer::route('/kredit-customer/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->orderBy('created_at', 'DESC');
    }
}

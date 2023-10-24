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
use Filament\Forms\Components\Card;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
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
                                    ->label('Status User')
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
                    ->width(75),
                TextColumn::make('name')
                    ->label('nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('no_hp')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->searchable(),
                TextColumn::make('location.nama')
                    ->label('Location')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->sortable()
                    ->dateTime(),
                TextColumn::make('status_formatted'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
            ])
            ->filters([
                //
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
                    Action::make('kredit')
                        ->icon('heroicon-o-arrow-circle-down')
                        ->label('View Kredit')
                        ->url(fn ($record) => CustomerResource::getUrl('kredit-customer', ['record' => $record]))
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
        });
    }
}

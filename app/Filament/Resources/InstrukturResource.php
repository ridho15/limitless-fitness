<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrukturResource\Pages;
use App\Filament\Resources\InstrukturResource\Pages\ViewInstruktur;
use App\Filament\Resources\InstrukturResource\RelationManagers\KelasActivityRelationManager;
use App\Models\BookingKelas;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
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
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yepsua\Filament\Tables\Components\RatingColumn;

class InstrukturResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User';

    protected static ?string $pluralModelLabel = 'Data Instruktur';

    protected static ?string $navigationLabel = 'Instruktur';

    protected static ?string $slug = 'instruktur';

    public static function form(Form $form): Form
    {
        $roles = Role::where('name', 'Instruktur')->first();
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Instruktur')
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
                                FileUpload::make('foto')
                                    ->image()
                                    ->maxSize(2048)
                                    ->nullable(),
                                Select::make('roles')
                                    ->label('Roles')
                                    ->multiple()
                                    ->default($roles ? [$roles->id] : null)
                                    ->relationship('roles', 'name')
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
                TextColumn::make('barcode')
                    ->label('Barcode')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
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
                    ->toggleable()
                    ->label('Tanggal Lahir'),
                TextColumn::make('email_verified_at')
                    ->label('Tanggal Verifikasi Email')
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),
                TextColumn::make('status_formatted')
                    ->toggleable()
                    ->label('Status User'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 2);
                    })
                    ->searchable(),
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
            ->filters([])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('Menghapus data instruktur');
                        }),
                    ViewAction::make(),
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
            KelasActivityRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstrukturs::route('/'),
            'create' => Pages\CreateInstruktur::route('/create'),
            'view' => ViewInstruktur::route('/{record}'),
            'edit' => Pages\EditInstruktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        });
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (Auth::user()->hasRole(['Super Admin'])) {
            return true;
        }
        return false;
    }
}

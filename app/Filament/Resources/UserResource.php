<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
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
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form User')
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
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                        Select::make('permissions')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload(),
                        Toggle::make('status')
                            ->label('Status User'),
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
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (Auth::user()->hasRole(['Super Admin'])) {
            return true;
        }

        return false;
    }
}

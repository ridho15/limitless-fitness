<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Filament\Resources\CoaResource\RelationManagers;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Chart Of Account';

    protected static ?string $pluralModelLabel = 'Chart Of Account';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Coa')
                    ->schema([
                        TextInput::make('nomor')
                            ->unique(ignoreRecord: true)
                            ->label('Nomor Rekening')
                            ->required(),
                        TextInput::make('nama')
                            ->label('Nama Rekening')
                            ->string()
                            ->required(),
                        TextInput::make('saldo')
                            ->label('Saldo')
                            ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                'uang' => fn (Mask $mask) => $mask->numeric()
                                    ->thousandsSeparator('.')
                                    ->decimalSeparator(',')
                            ])->pattern('Rp.uang'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('saldo_formatted'),
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}

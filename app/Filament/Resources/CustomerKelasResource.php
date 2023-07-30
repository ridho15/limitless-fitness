<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerKelasResource\Pages;
use App\Filament\Resources\CustomerKelasResource\RelationManagers;
use App\Models\CustomerKelas;
use App\Models\Kelas;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerKelasResource extends Resource
{
    protected static ?string $model = CustomerKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationLabel = 'Kelas Order';

    protected static ?string $pluralModelLabel = 'Kelas Order';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $customer = User::whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->get()->pluck('name', 'id');
        return $form
            ->schema([
                Fieldset::make('Form Customer Kelas')
                    ->schema([
                        Select::make('id_user')
                            ->label('Peserta')
                            ->searchable()
                            ->options($customer)
                            ->preload()
                            ->required(),
                        Select::make('id_kelas')
                            ->searchable()
                            ->reactive()
                            ->relationship('kelas', 'nama_kelas')
                            ->afterStateUpdated(function (Closure $set, $state) {
                                $kelas = Kelas::find($state);
                                if ($kelas) {
                                    $set('jumlah_pertemuan ', $kelas->jumlah_pertemuan);
                                }
                            })
                            ->preload()
                            ->required(),
                        DateTimePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->nullable(),
                        TextInput::make('jumlah_pertemuan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        FileUpload::make('bukti_bayar')
                            ->image()
                            ->maxSize('2048')
                            ->nullable(),
                        Radio::make('status')
                            ->label('Status Peserta')
                            ->options([
                                '1' => 'Avaiable',
                                '0' => 'Dibatalkan',
                                '2' => 'Belum Bayar'
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Peserta')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('status_formatted')
                    ->label('Status'),
                TextColumn::make('tanggal_bayar')
                    ->sortable(),
                ImageColumn::make('bukti_bayar'),
                TextColumn::make('jumlah_pertemuan')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
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
            'index' => Pages\ListCustomerKelas::route('/'),
            'create' => Pages\CreateCustomerKelas::route('/create'),
            'edit' => Pages\EditCustomerKelas::route('/{record}/edit'),
        ];
    }
}

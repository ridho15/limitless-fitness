<?php

namespace App\Filament\Resources\VoucherResource\RelationManagers;

use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherCustomerRelationManager extends RelationManager
{
    protected static string $relationship = 'VoucherCustomer';

    protected static ?string $recordTitleAttribute = 'id_voucher';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Voucher Customer')
                    ->schema([
                        Select::make('id_customer')
                            ->label('Pilih Customer')
                            ->preload()
                            ->searchable()
                            ->options(function (Closure $get) {
                                $customer = User::whereHas('roles', function ($query) {
                                    $query->where('name', 'Customer');
                                })->get()->pluck('name', 'id');

                                return $customer;
                            })->required(),
                        DateTimePicker::make('expired')
                            ->label('Expired Date')
                            ->required(),
                        DateTimePicker::make('tanggal_digunakan')
                            ->label('Tanggal Digunakan')
                            ->nullable(),
                        Radio::make('status')
                            ->label('Status Aktif')
                            ->options([
                                0 => 'Tidak Aktif',
                                1 => 'Aktif'
                            ]),
                        Radio::make('status_digunakan')
                            ->label('Status Digunakan')
                            ->options([
                                0 => 'Belum digunakan',
                                1 => 'Sudah Digunakan'
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Nama Customer')
                    ->searchable(),
                TextColumn::make('expired')
                    ->label('Expired Date'),
                BadgeColumn::make('status')
                    ->label('Status Aktif')
                    ->colors([
                        'secondary',
                        'success' => 1
                    ])
                    ->enum([
                        0 => 'Tidak aktif',
                        1 => 'Aktif'
                    ]),
                BadgeColumn::make('status_digunakan')
                    ->label('Status Digunakan')
                    ->colors([
                        'success' => 0,
                        'secondary' => 1,
                    ])->enum([
                        0 => 'Belum digunakan',
                        1 => 'Sudah digunakan'
                    ]),
                TextColumn::make('tanggal_digunakan')
                    ->label('Tanggal Digunakan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Voucher Customer')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

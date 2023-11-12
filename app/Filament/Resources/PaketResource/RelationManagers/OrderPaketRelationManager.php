<?php

namespace App\Filament\Resources\PaketResource\RelationManagers;

use App\Models\Paket;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderPaketRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderPaket';

    protected static ?string $recordTitleAttribute = 'id_paket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_user')
                    ->searchable()
                    ->preload()
                    ->label('Customer')
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Customer');
                    })->pluck('name', 'id'))
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
                TextColumn::make('jumlah_pertemuan')
                    ->sortable(),
                TextColumn::make('sisa')
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
            ->headerActions([
                CreateAction::make()
                ->mutateFormDataUsing(function($data){
                    $data['sisa'] = $data['jumlah_pertemuan'];
                    return $data;
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        return parent::getTableQuery()->orderBy('created_at', 'DESC');
    }
}

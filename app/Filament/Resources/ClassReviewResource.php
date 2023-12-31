<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassReviewResource\Pages;
use App\Filament\Resources\ClassReviewResource\RelationManagers;
use App\Models\BookingKelas;
use App\Models\ClassReview;
use App\Models\KelasRating;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Yepsua\Filament\Tables\Components\RatingColumn;

class ClassReviewResource extends Resource
{
    protected static ?string $model = BookingKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $pluralModelLabel = 'Class Review';

    protected static ?string $slug = 'class-review';

    protected static ?string $title = 'Class Review';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelasActivity.user.name')
                    ->searchable()
                    ->label('Instruktur'),
                TextColumn::make('kelasActivity.kelas.nama_kelas')
                    ->searchable()
                    ->label('Kelas'),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Customer'),
                TextColumn::make('keterangan')
                    ->label('Notes')
                    ->searchable(),
                TextColumn::make('review')
                    ->label('Review')
                    ->searchable(),
                RatingColumn::make('rating')
                    ->label('Rating'),
                TextColumn::make('updated_at')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm');
                    })
            ])
            ->filters([
                SelectFilter::make('id_user')
                    ->label('Customer')
                    ->searchable()
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Customer');
                    })->get()->pluck('name', 'id')),
                SelectFilter::make('id_instruktur')
                    ->label('Instruktur')
                    ->searchable()
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Instruktur');
                    })->get()->pluck('name', 'id'))->query(function (Builder $query, $data) {
                        $query->when($data['value'], function (Builder $query, $id_instruktur) {
                            $query->whereHas('kelasActivity', function ($query) use ($id_instruktur) {
                                $query->where('id_user', $id_instruktur);
                            });
                        });
                    })
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                ])
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
            'index' => Pages\ListClassReviews::route('/'),
            // 'create' => Pages\CreateClassReview::route('/create'),
            // 'edit' => Pages\EditClassReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->when(Auth::user()->hasRole(['Instruktur']), function ($query, $id_instruktur) {
            $query->whereHas('kelasActivity', function ($query) {
                $query->where('id_user', Auth::id());
            });
        })->whereNotNull('review')->orderBy('updated_at', 'DESC');
    }
}

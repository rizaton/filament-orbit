<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;

use App\Models\Rental;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\RentalResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RentalResource\RelationManagers;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Penyewaan';
    protected static ?string $slug = 'rent/rentals';

    protected static ?string $navigationLabel = 'List Penyewaan';
    protected static ?string $pluralModelLabel = 'List Penyewaan-penyewaan';
    protected static ?string $modelLabel = 'Penyewaan';
    protected static ?string $breadcrumb = 'Penyewaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('performed_by')
                    ->label('Performed By')
                    ->options(User::all()->pluck('name', 'id'))
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('User Name')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('User Email')
                            ->email()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->required(),
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'rented' => 'Rented',
                        'returned' => 'Returned',
                        'late' => 'Late',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('down_payment')
                    ->maxLength(15)
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('rent_date')
                    ->required(),
                Forms\Components\DatePicker::make('return_date')
                    ->required(),
                Forms\Components\DatePicker::make('late_date'),
                Forms\Components\TextInput::make('late_fees')
                    ->maxLength(15)
                    ->numeric(),
                Forms\Components\TextInput::make('total_fees')
                    ->maxLength(15)
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('down_payment')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_fees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_fees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'returned' => 'Returned',
                        'late' => 'Late',
                    ])->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada penyewaan yang ditemukan')
            ->emptyStateDescription('Saat ini tidak ada data penyewaan')
            ->emptyStateIcon('heroicon-o-briefcase');
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
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }
}

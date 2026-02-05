<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantTableResource\Pages;
use App\Models\RestaurantTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RestaurantTableResource extends Resource
{
    protected static ?string $model = RestaurantTable::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    
    protected static ?string $modelLabel = 'Mesa';

    protected static ?string $pluralModelLabel = 'Mesas';

    protected static ?string $navigationGroup = 'Gerenciamento do Restaurante';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                auth()->user()->isAdmin()
                    ? Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->label('Restaurante')
                        ->required()
                        ->searchable()
                        ->preload()
                    : Forms\Components\Hidden::make('user_id')
                        ->default(auth()->id()),
                Forms\Components\TextInput::make('number')
                    ->label('Número / Nome da Mesa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('capacity')
                    ->label('Capacidade')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Mesa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRestaurantTables::route('/'),
            'create' => Pages\CreateRestaurantTable::route('/create'),
            'edit' => Pages\EditRestaurantTable::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
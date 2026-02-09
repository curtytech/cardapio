<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellResourceSimple\Pages;
use App\Filament\Resources\SellResource\RelationManagers;
use App\Models\Sell;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SellResourceSimple extends Resource
{
    protected static ?string $model = Sell::class;

    // Slug único para este resource, evitando conflito com SellResource
    protected static ?string $slug = 'vendas-simples';

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $modelLabel = 'Venda Simples';
    protected static ?string $pluralModelLabel = 'Vendas Simples';
    protected static ?string $navigationLabel = 'Vendas Simples';

    protected static ?string $navigationGroup = 'Gerenciamento de Vendas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('cliente')
                    ->extraAttributes(['class' => 'hidden'])
                    ->schema([
                        Forms\Components\TextInput::make('user_id')
                            ->label('Cliente')
                            // ->hidden(auth()->user()->role !== 'admin')
                            ->numeric()
                            ->required()
                            ->default(auth()->id()),
                    ]),
                Forms\Components\Section::make('Produtos')
                    ->schema([
                        FormActions::make(
                            Product::query()
                                ->where('user_id', auth()->id())
                                ->get()
                                ->map(function (Product $product) {
                                    return Action::make('add_product_' . $product->id)
                                        ->label(function (Get $get) use ($product) {
                                            $items = $get('sellProductsGroups') ?? [];
                                            $qty = 0;

                                            foreach ($items as $item) {
                                                if (($item['product_id'] ?? null) === $product->id) {
                                                    $qty = (int) ($item['quantity'] ?? 0);
                                                    break;
                                                }
                                            }

                                            return $qty > 0
                                                ? $product->name . ' (' . $qty . ')'
                                                : $product->name;
                                        })
                                        ->icon('heroicon-o-plus-circle')
                                        ->color('primary')
                                        ->button()
                                        ->action(function (Set $set, Get $get) use ($product) {
                                            $items = $get('sellProductsGroups') ?? [];
                                            $found = false;
                                            foreach ($items as $index => $item) {
                                                if (($item['product_id'] ?? null) === $product->id) {
                                                    $items[$index]['quantity'] = (int) ($item['quantity'] ?? 0) + 1;
                                                    $found = true;
                                                    break;
                                                }
                                            }

                                            if (! $found) {
                                                $items[] = [
                                                    'product_id' => $product->id,
                                                    'quantity' => 1,
                                                ];
                                            }

                                            $set('sellProductsGroups', $items); 
                                        });
                                })
                                ->all()
                        )->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('sellProductsGroups')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship(
                                name: 'product',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('user_id', auth()->id()),
                            )
                            ->label('Produto')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantidade')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->addable(false)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('date')
                    ->label('Data')
                    ->default(now()),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Pago?')
                    ->default(true)
                    ->required(),
            ]);
    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->state(function (Sell $record): float {
                        return $record->sellProductsGroups->sum(function ($pq) {
                            return $pq->quantity * ($pq->product->sell_price ?? 0);
                        });
                    })
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Pago?')
                    ->boolean(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

     public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['sellProductsGroups.product']);

        if (auth()->user()?->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
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
            'index' => Pages\ListSellSimple::route('/'),
            'create' => Pages\CreateSellSimple::route('/create'),
            'edit' => Pages\EditSellSimple::route('/{record}/edit'),
        ];
    }
}

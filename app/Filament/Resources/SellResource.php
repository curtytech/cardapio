<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellResource\Pages;
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

class SellResource extends Resource
{
    protected static ?string $model = Sell::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $modelLabel = 'Venda';
    protected static ?string $pluralModelLabel = 'Vendas';
    protected static ?string $navigationLabel = 'Vendas';

    protected static ?string $navigationGroup = 'Gerenciamento de Vendas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('table_id')
                    ->label('Mesa')
                    ->relationship('restaurantTable', 'number', function ($query) {
                        // Se o usuário logado for 'user', só pode ver suas próprias mesas
                        if (auth()->user()->role === 'user') {
                            $query->where('user_id', auth()->id());
                        }
                    })
                    ->default(fn () => request()->get('table_id'))
                    ->searchable()
                    ->preload()
                    ->required(),
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
                                                ? $product->name . ' - R$ ' . number_format($product->sell_price, 2, ',', '.') . ' (' . $qty . ')'
                                                : $product->name . ' - R$ ' . number_format($product->sell_price, 2, ',', '.');
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
                Forms\Components\Section::make('sellProductsGroups')
                    ->extraAttributes(['class' => 'hidden'])
                    ->schema([

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
                            ->columnSpanFull()
                            ->defaultItems(0)
                            ->addable(false)
                            ->extraAttributes(['class' => 'hidden']),
                    ]),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Placeholder::make('total_value')
                            ->label('Valor Total')
                            ->content(function (Get $get) {
                                $items = $get('sellProductsGroups') ?? [];
                                if (empty($items)) {
                                    return 'R$ 0,00';
                                }

                                $productIds = collect($items)->pluck('product_id')->filter();
                                if ($productIds->isEmpty()) {
                                    return 'R$ 0,00';
                                }

                                $products = Product::whereIn('id', $productIds)->pluck('sell_price', 'id');

                                $total = 0;
                                foreach ($items as $item) {
                                    $pid = $item['product_id'] ?? null;
                                    $qty = (int) ($item['quantity'] ?? 0);
                                    $price = $products->get($pid) ?? 0;
                                    $total += $qty * $price;
                                }

                                return 'R$ ' . number_format($total, 2, ',', '.');
                            }),

                        Forms\Components\TextInput::make('client_name')
                            ->label('Nome do Cliente')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Data')
                            ->default(now()),
                        Forms\Components\Toggle::make('is_paid')
                            ->label('Pago?')
                            ->default(true)
                            ->required(),
                        Forms\Components\Toggle::make('is_finished')
                            ->label('Finalizado?')
                            ->default(true)
                            ->required(),
                    ]),
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
                Tables\Columns\TextColumn::make('mesa_virtual')
                    ->label('Mesa')
                    ->getStateUsing(fn (Sell $record) => 'Mesa ' . $record->table_id)
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
        $query = parent::getEloquentQuery()->with([
            'sellProductsGroups.product',
            'restaurantTable',
        ]);

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
            'index' => Pages\ListSells::route('/'),
            'create' => Pages\CreateSell::route('/create'),
            'edit' => Pages\EditSell::route('/{record}/edit'),
        ];
    }
}

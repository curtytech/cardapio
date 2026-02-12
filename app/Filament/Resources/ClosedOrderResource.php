<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClosedOrderResource\Pages\ListClosedOrders;
use App\Models\Sell;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class ClosedOrderResource extends Resource
{
    protected static ?string $model = Sell::class;
    protected static ?string $navigationLabel = 'Pedidos Fechados';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Gerenciamento do Restaurante';

    /**
     * Apenas pedidos finalizados
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->where('is_finished', true)
            ->with([
                'restaurantTable',
                'sellProductsGroups.product',
            ]);

        if (auth()->check() && auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    /**
     * Tabela principal (Admin)
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('restaurantTable.number')
                    ->label('Mesa')
                    ->sortable(),

                Tables\Columns\TextColumn::make('client_name')
                    ->label('Cliente')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sellProductsGroups')
                    ->label('Produtos')
                    ->formatStateUsing(function (Sell $record) {
                        return $record->sellProductsGroups
                            ->map(
                                fn($group) => ($group->product?->name ?? 'Produto removido') . ' x ' . $group->quantity
                            )
                            ->join(', ');
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable(),

                Tables\Columns\TextColumn::make('observation')
                    ->label('Observações')
                    ->limit(30)
                    ->tooltip(fn(Sell $record) => $record->observation),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Pago')
                    ->boolean()
                    ->sortable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('table_id')
                    ->label('Mesa')
                    ->relationship('restaurantTable', 'number'),

                Tables\Filters\Filter::make('created_at')
                    ->label('Período')
                    ->form([
                        DatePicker::make('from')
                            ->label('De'),
                        DatePicker::make('until')
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q) => $q->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(
                                $data['until'],
                                fn($q) => $q->whereDate('created_at', '<=', $data['until'])
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('reopen')
                    ->label('Reabrir Pedido')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(Sell $record) => $record->update([
                        'is_finished' => false,
                    ])),

                Tables\Actions\Action::make('markAsPaid')
                    ->label('Marcar como pago')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Sell $record) => ! $record->is_paid)
                    ->action(function (Sell $record) {
                        $record->update([
                            'is_paid' => true,
                        ]);
                    }),
            ])
            ->defaultSort('created_at', 'asc');
    }


    /**
     * Páginas do resource
     */
    public static function getPages(): array
    {
        return [
            'index' => ListClosedOrders::route('/'),
        ];
    }
}

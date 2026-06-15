<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages\ListDeliveries;
use App\Models\Delivery;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Gerenciamento de Vendas';

    protected static ?string $navigationLabel = 'Vendas Delivery';

    protected static ?string $modelLabel = 'Venda Delivery';

    protected static ?string $pluralModelLabel = 'Vendas Delivery';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'sell.sellProductsGroups.product',
                'user',
            ]);

        if (auth()->check() && auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning',
                        'enviado' => 'info',
                        'entregue' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_phone')
                    ->label('Telefone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Endereco')
                    ->formatStateUsing(fn (Delivery $record): string => collect([
                        $record->address,
                        $record->number,
                        $record->neighborhood,
                        $record->city,
                        $record->state,
                    ])->filter()->join(', '))
                    ->wrap(),
                Tables\Columns\TextColumn::make('products')
                    ->label('Produtos')
                    ->state(function (Delivery $record): string {
                        return $record->sell?->sellProductsGroups
                            ?->map(fn ($group) => ($group->product?->name ?? 'Produto removido') . ' x ' . $group->quantity)
                            ->join(', ') ?? '-';
                    })
                    ->wrap(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pagamento')
                    ->formatStateUsing(fn (?string $state): string => $state ? str_replace('_', ' ', ucfirst($state)) : '-'),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Pago')
                    ->boolean(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_fee')
                    ->label('Taxa')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'enviado' => 'Enviado',
                        'entregue' => 'Entregue',
                        'cancelado' => 'Cancelado',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->label('Periodo')
                    ->form([
                        DatePicker::make('from')->label('De'),
                        DatePicker::make('until')->label('Ate'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('markAsSent')
                    ->label('Marcar como enviado')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn (Delivery $record): bool => $record->status === 'pendente')
                    ->requiresConfirmation()
                    ->action(fn (Delivery $record) => $record->update([
                        'status' => 'enviado',
                        'sent_at' => now(),
                    ])),
                Tables\Actions\Action::make('markAsDelivered')
                    ->label('Marcar como entregue')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Delivery $record): bool => in_array($record->status, ['pendente', 'enviado'], true))
                    ->requiresConfirmation()
                    ->action(fn (Delivery $record) => $record->update([
                        'status' => 'entregue',
                        'sent_at' => $record->sent_at ?? now(),
                        'delivered_at' => now(),
                    ])),
                Tables\Actions\Action::make('markAsPaid')
                    ->label('Marcar como pago')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Delivery $record): bool => ! $record->is_paid)
                    ->requiresConfirmation()
                    ->action(fn (Delivery $record) => $record->update([
                        'is_paid' => true,
                    ])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveries::route('/'),
        ];
    }
}

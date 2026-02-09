<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpenOrderResource\Pages\ListOpenOrders;
use App\Models\Sell;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class OpenOrderResource extends Resource
{
    protected static ?string $model = Sell::class;

    protected static ?string $navigationLabel = 'Pedidos em Aberto';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Gerenciamento do Restaurante';

    /**
     * Apenas pedidos não finalizados
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_finished', false)
            ->with([
                'table',
                'sellProductsGroups.product',
            ]);
    }

    /**
     * Tabela principal (Admin)
     */
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('table.name')
                ->label('Mesa')
                ->sortable(),

            Tables\Columns\TextColumn::make('client_name')
                ->label('Cliente')
                ->searchable(),

            Tables\Columns\TextColumn::make('sellProductsGroups')
                ->label('Produtos')
                ->formatStateUsing(function (Sell $record) {
                    return $record->sellProductsGroups
                        ->map(fn ($group) =>
                            ($group->product?->name ?? 'Produto removido') . ' x ' . $group->quantity
                        )
                        ->join(', ');
                })
                ->wrap(),

            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('BRL')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Criado em')
                ->dateTime('d/m/Y H:i'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('table_id')
                ->label('Mesa')
                ->relationship('table', 'number'),

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
                            fn ($q) => $q->whereDate('created_at', '>=', $data['from'])
                        )
                        ->when(
                            $data['until'],
                            fn ($q) => $q->whereDate('created_at', '<=', $data['until'])
                        );
                }),
        ])
        ->actions([
            Tables\Actions\Action::make('finalizar')
                ->label('Finalizar Pedido')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn (Sell $record) => $record->update([
                    'is_finished' => true,
                ])),
        ])
        ->defaultSort('created_at', 'asc');
}


    /**
     * Páginas do resource
     */
    public static function getPages(): array
    {
        return [
            'index' => ListOpenOrders::route('/'),
        ];
    }
}

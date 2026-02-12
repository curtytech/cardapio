<?php

namespace App\Filament\Widgets;

use App\Models\Sell;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class SalesOfMonthTable extends BaseWidget
{
    protected static ?string $heading = 'Relatório de Vendas';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sell::query()
                    ->when(auth()->user()->role !== 'admin', fn($q) => $q->where('user_id', auth()->id()))
                    ->latest('date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Cliente'),
                Tables\Columns\TextColumn::make('total_calculated')
                    ->label('Total')
                    ->state(function (Sell $record) {
                        return $record->sellProductsGroups->sum(function ($item) {
                            return $item->quantity * ($item->product->sell_price ?? 0);
                        });
                    })
                    ->money('BRL'),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Pago')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_from')
                            ->label('De'),
                        \Filament\Forms\Components\DatePicker::make('date_until')
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date) => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date) => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Sell $record) => route('sells.print', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
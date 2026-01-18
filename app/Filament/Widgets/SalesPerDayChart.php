<?php

namespace App\Filament\Widgets;

use App\Models\Sell;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesPerDayChart extends ChartWidget
{
    protected static ?string $heading = 'Vendas por dia';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $results = Sell::query()
            ->selectRaw('DATE(sells.date) as date, SUM(products_quantities.quantity * products.sell_price) as aggregate')
            ->join('products_quantities', 'products_quantities.sell_id', '=', 'sells.id')
            ->join('products', 'products_quantities.product_id', '=', 'products.id')
            ->where('sells.user_id', auth()->id())
            ->whereBetween('sells.date', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with 0
        $data = collect();
        $current = $start->copy();

        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $record = $results->firstWhere('date', $dateStr);
            
            $data->push([
                'date' => $dateStr,
                'aggregate' => $record ? $record->aggregate : 0,
            ]);

            $current->addDay();
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Total de Vendas (R$)',
                    'data' => $data->pluck('aggregate'),
                ],
            ],
            'labels' => $data->pluck('date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

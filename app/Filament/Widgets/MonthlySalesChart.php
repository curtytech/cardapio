<?php

namespace App\Filament\Widgets;

use App\Models\Sell;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Comparativo de Vendas (Últimos 12 meses)';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // Fetch sales for the last 12 months
        $data = Sell::query()
            ->when(auth()->check() && auth()->user()->role !== 'admin', fn($q) => $q->where('user_id', auth()->id()))
            ->whereDate('date', '>=', now()->subMonths(11)->startOfMonth())
            ->with(['sellProductsGroups.product'])
            ->get()
            ->groupBy(function($sale) {
                return Carbon::parse($sale->date)->format('Y-m');
            });

        $labels = [];
        $values = [];
        
        // Iterate over the last 12 months to ensure all months are represented
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            
            $monthSales = $data->get($monthKey);
            $total = 0;
            
            if ($monthSales) {
                foreach ($monthSales as $sale) {
                    $total += $sale->sellProductsGroups->sum(function($item) {
                        return $item->quantity * ($item->product->sell_price ?? 0);
                    });
                }
            }
            
            $values[] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Vendas (R$)',
                    'data' => $values,
                    'borderColor' => '#10B981', // green
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
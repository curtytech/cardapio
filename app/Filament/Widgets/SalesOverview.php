<?php

namespace App\Filament\Widgets;

use App\Models\Sell;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Sell::join('products_quantities', 'sells.id', '=', 'products_quantities.sell_id')
            ->join('products', 'products_quantities.product_id', '=', 'products.id')
            ->where('sells.user_id', auth()->id())
            ->sum(DB::raw('products_quantities.quantity * products.sell_price'));

        $todayRevenue = Sell::join('products_quantities', 'sells.id', '=', 'products_quantities.sell_id')
            ->join('products', 'products_quantities.product_id', '=', 'products.id')
            ->where('sells.user_id', auth()->id())
            ->whereDate('sells.date', today())
            ->sum(DB::raw('products_quantities.quantity * products.sell_price'));

        return [
            Stat::make('Total de Vendas', 'R$ ' . number_format($totalRevenue, 2, ',', '.'))
                ->description('Receita total')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Vendas Hoje', 'R$ ' . number_format($todayRevenue, 2, ',', '.'))
                ->description('Receita de hoje')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\SalesOfMonthTable;
use App\Filament\Widgets\MonthlySalesChart;

class SalesReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Relatórios';
    protected static ?string $title = 'Relatórios de Vendas';
    protected static ?string $navigationGroup = 'Gerenciamento de Vendas';
    
    // Define the view for this page
    protected static string $view = 'filament.pages.sales-reports';

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlySalesChart::class,
            SalesOfMonthTable::class,
        ];
    }
}
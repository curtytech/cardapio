<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UsersLast30DaysChart extends ChartWidget
{
    protected static ?string $heading = 'Usuários nos últimos 30 dias';

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()?->role === 'admin';
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $end = Carbon::today();
        $start = $end->copy()->subDays(29);

        $rows = User::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $dateStr = $current->toDateString();
            $labels[] = $current->format('d/m');
            $data[] = (int) ($rows[$dateStr]->count ?? 0);
            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Novos usuários por dia',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
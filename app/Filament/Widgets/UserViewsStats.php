<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UserViewsStats extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()?->role === 'user';
    }

    protected function getCards(): array
    {
        $userId = auth()->id();
        $views = \App\Models\UserPageView::where('user_id', $userId)->value('views_count') ?? 0;

        return [
            Card::make('Visualizações da minha página', $views)
                ->description('Total de acessos ao seu link público')
                ->icon('heroicon-o-eye')
                ->color('primary'),
        ];
    }
}
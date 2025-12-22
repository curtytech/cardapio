<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UserQrWidget extends Widget
{
    // ... existing code ...
    protected static string $view = 'filament.widgets.user-qr-widget';
    protected int|string|array $columnSpan = 'full';
    // ... existing code ...

    public function getViewData(): array
    {
        $user = auth()->user();
        $slug = $user?->slug;
        $base = env('APP_URL') . '/';
        $url = $slug ? $base . $slug : null;
        $qrApi = $url ? 'https://api.qrserver.com/v1/create-qr-code/?size=480x480&data=' . urlencode($url) : null;

        return compact('user', 'url', 'qrApi');
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}
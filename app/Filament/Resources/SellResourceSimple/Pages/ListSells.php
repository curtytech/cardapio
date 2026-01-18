<?php

namespace App\Filament\Resources\SellResourceSimple\Pages;

use App\Filament\Resources\SellResourceSimple;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellSimple extends ListRecords
{
    protected static string $resource = SellResourceSimple::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

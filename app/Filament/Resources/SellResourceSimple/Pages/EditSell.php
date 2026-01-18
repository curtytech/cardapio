<?php

namespace App\Filament\Resources\SellResourceSimple\Pages;

use App\Filament\Resources\SellResourceSimple;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSellSimple extends EditRecord
{
    protected static string $resource = SellResourceSimple::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ClosedOrderResource\Pages;

use App\Filament\Resources\ClosedOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListClosedOrders extends ListRecords
{
    protected static string $resource = ClosedOrderResource::class;
}

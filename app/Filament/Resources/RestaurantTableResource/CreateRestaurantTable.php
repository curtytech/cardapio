<?php

namespace App\Filament\Resources\RestaurantTableResource\Pages;

use App\Filament\Resources\RestaurantTableResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantTable extends CreateRecord
{
    protected static string $resource = RestaurantTableResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->isAdmin()) {
            $data['user_id'] = auth()->id();
        }
        
        return $data;
    }
}
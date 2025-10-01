<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Se o usuário logado for 'user', definir automaticamente o user_id
        if (auth()->user()->role === 'user') {
            $data['user_id'] = auth()->id();
        }
        
        return $data;
    }
}

<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Se o usuário logado for 'user', definir automaticamente o user_id
        if (auth()->user()->role === 'user') {
            $data['user_id'] = auth()->id();
        }
        
        return $data;
    }
}

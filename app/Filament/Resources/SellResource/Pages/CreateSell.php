<?php

namespace App\Filament\Resources\SellResource\Pages;

use App\Filament\Resources\SellResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSell extends CreateRecord
{
    protected static string $resource = SellResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Se o usuário logado for 'user', definir automaticamente o user_id
        if (auth()->user()->role === 'user') {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
}

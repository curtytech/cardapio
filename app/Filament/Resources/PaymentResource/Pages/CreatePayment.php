<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['data_pagamento'])) {
            $data['expiration_date'] = Carbon::parse($data['data_pagamento'])->addYear();
        }

        return $data;
    }

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->role === 'admin';
    }
}
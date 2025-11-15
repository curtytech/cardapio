<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('viewPage')
                ->label('Ver Página')
                ->icon('heroicon-o-eye')
                ->url(fn () => route('menu.show', $this->record->slug))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Após salvar, vai direto para a página pública do usuário
        return route('menu.show', $this->record->slug);
    }
}

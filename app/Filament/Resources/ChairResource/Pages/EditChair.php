<?php

namespace App\Filament\Resources\ChairResource\Pages;

use App\Filament\Resources\ChairResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChair extends EditRecord
{
    protected static string $resource = ChairResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

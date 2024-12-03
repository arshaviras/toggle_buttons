<?php

namespace App\Filament\Resources\ChairResource\Pages;

use App\Filament\Resources\ChairResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChair extends CreateRecord
{
    protected static string $resource = ChairResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

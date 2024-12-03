<?php

namespace App\Filament\Resources\ChairResource\Pages;

use App\Filament\Resources\ChairResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChair extends ViewRecord
{
    protected static string $resource = ChairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Performance: string implements HasColor, HasIcon, HasLabel
{
    case Satisfactory = 'satisfactory';

    case Good = 'good';

    case Excellent = 'excellent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Satisfactory => __('Satisfactory'),
            self::Good => __('Good'),
            self::Excellent => __('Excellent'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Satisfactory => 'danger',
            self::Good => 'warning',
            self::Excellent => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Satisfactory => 'heroicon-m-hand-thumb-down',
            self::Good => 'heroicon-m-face-smile',
            self::Excellent => 'heroicon-m-hand-thumb-up',
        };
    }
}

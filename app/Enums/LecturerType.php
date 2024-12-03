<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LecturerType: string implements HasColor, HasIcon, HasLabel
{
    case Practice = 'practice';

    case Lecture = 'lecture';

    public function getLabel(): string
    {
        return match ($this) {
            self::Practice => __('Practice'),
            self::Lecture => __('Lecture'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Practice => 'info',
            self::Lecture => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Practice => 'heroicon-m-beaker',
            self::Lecture => 'heroicon-m-newspaper',
        };
    }
}

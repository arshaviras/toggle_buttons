<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum QuestionnaireType: string implements HasColor, HasIcon, HasLabel
{
    case Practice = 'practice';
    case Lecture = 'lecture';
    case Chair = 'chair';

    public function getLabel(): string
    {
        return match ($this) {
            self::Practice => __('Practice'),
            self::Lecture => __('Lecture'),
            self::Chair => __('Chair'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Practice => 'info',
            self::Lecture => 'warning',
            self::Chair => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Practice => 'heroicon-m-beaker',
            self::Lecture => 'heroicon-m-newspaper',
            self::Chair => 'heroicon-m-squares-2x2',
        };
    }
}

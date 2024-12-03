<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum GroupType: string implements HasColor, HasIcon, HasLabel
{
    case Armenian = 'armenian';

    case English = 'english';

    case Russian = 'russian';

    public function getLabel(): string
    {
        return match ($this) {
            self::Armenian => __('Armenian'),
            self::English => __('English'),
            self::Russian => __('Russian'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Armenian => 'success',
            self::English => 'info',
            self::Russian => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Armenian => 'icon-am-flag',
            self::English => 'icon-en-flag',
            self::Russian => 'icon-ru-flag',
        };
    }
}

<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PhoneCountries: int implements HasLabel
{
    // case INDIA = 0;
    case AMERICAN = 1;
    case JAPAN = 2;
    case MIDDLE_EASTERN = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AMERICAN => 'American',
            self::JAPAN => 'Japanese',
            self::MIDDLE_EASTERN => 'Middle Eastern',
        };
    }
}

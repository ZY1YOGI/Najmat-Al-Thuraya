<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PhoneSim: int implements HasLabel
{
    case ONE = 1;
    case TWO = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ONE => '1 SIM',
            self::TWO => '2 SIM',
        };
    }
}

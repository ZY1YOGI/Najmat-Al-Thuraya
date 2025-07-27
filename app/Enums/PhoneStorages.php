<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PhoneStorages: int implements HasLabel
{

    case GB64 = 1;
    case GB128 = 2;
    case GB256 = 3;
    case GB512 = 4;
    case TB1 = 5;


    public function getLabel(): ?string
    {
        return match ($this) {
            self::GB64 => '64 GB',
            self::GB128 => '128 GB',
            self::GB256 => '256 GB',
            self::GB512 => '512 GB',
            self::TB1 => '1 TB',
        };
    }
}

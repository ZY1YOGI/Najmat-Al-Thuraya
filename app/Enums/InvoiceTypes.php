<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum InvoiceTypes: int implements HasLabel, HasColor
{
    case BUY = 1;
    case SELL = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BUY => 'Buy',
            self::SELL => 'Sell',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BUY => 'primary',
            self::SELL => 'success',
        };
    }
}

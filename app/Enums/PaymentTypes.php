<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum PaymentTypes: int implements HasLabel, HasIcon, HasColor
{
    case TAKE_FROM = 1;
    case GIVE_HIS = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TAKE_FROM => trans('field.take_from'),
            self::GIVE_HIS => trans('field.give_his'),
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TAKE_FROM => 'heroicon-o-currency-dollar',
            self::GIVE_HIS => 'heroicon-o-credit-card',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TAKE_FROM => 'success',
            self::GIVE_HIS => 'danger',
        };
    }
}

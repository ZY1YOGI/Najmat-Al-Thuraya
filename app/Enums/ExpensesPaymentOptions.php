<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum ExpensesPaymentOptions: int implements HasLabel, HasIcon
{
    // case UNKNOWN = 0;
    case CASH = 1;
    case CREDIT_CARD = 2;
    case BANK_TRANSFER = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            // self::UNKNOWN => trans('field.unknown'),
            self::CASH => trans('field.cash'),
            self::CREDIT_CARD => trans('field.credit_card'),
            self::BANK_TRANSFER => trans('field.bank_transfer'),
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            // self::UNKNOWN => 'heroicon-o-question-mark-circle',
            self::CASH => 'heroicon-o-currency-dollar',
            self::CREDIT_CARD => 'heroicon-o-credit-card',
            self::BANK_TRANSFER => 'heroicon-o-banknotes',
        };
    }
}

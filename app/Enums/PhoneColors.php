<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PhoneColors: int implements HasLabel
{
    case Silver = 1;
    case SpaceGray = 2;
    case Gold = 3;
    case MidnightGreen = 4;
    case PacificBlue = 5;
    case SierraBlue = 6;
    case SpaceBlack = 7;
    case DeepPurple = 8;
    case BlackTitanium = 9;
    case WhiteTitanium = 10;
    case BlueTitanium = 11;
    case NaturalTitanium = 12;
    case DesertTitanium = 13;
    case Black = 14;
    case White = 15;
    case Green = 16;
    case Yellow = 17;
    case Purple = 18;
    case Red = 19;
    case Blue = 20;
    case Pink = 21;
    case Teal = 22;
    case Ultramarine = 23;
    case Graphite = 24;
    case Midnight = 25;
    case Starlight = 26;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Silver => 'Silver',
            self::SpaceGray => 'Space Gray',
            self::Gold => 'Gold',
            self::MidnightGreen => 'Midnight Green',
            self::PacificBlue => 'Pacific Blue',
            self::SierraBlue => 'Sierra Blue',
            self::SpaceBlack => 'Space Black',
            self::DeepPurple => 'Deep Purple',
            self::BlackTitanium => 'Black Titanium',
            self::WhiteTitanium => 'White Titanium',
            self::BlueTitanium => 'Blue Titanium',
            self::NaturalTitanium => 'Natural Titanium',
            self::DesertTitanium => 'Desert Titanium',
            self::Black => 'Black',
            self::White => 'White',
            self::Green => 'Green',
            self::Yellow => 'Yellow',
            self::Purple => 'Purple',
            self::Red => 'Red',
            self::Blue => 'Blue',
            self::Pink => 'Pink',
            self::Teal => 'Teal',
            self::Ultramarine => 'Ultramarine',
            self::Graphite => 'Graphite',
            self::Midnight => 'Midnight',
            self::Starlight => 'Starlight',
        };
    }
}

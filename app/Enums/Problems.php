<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Problems: int implements HasLabel
{
    case BROKEN_SCREEN = 1;
    case BATTERY_ISSUE = 2;
    case NOT_CHARGING = 3;
    case NO_POWER = 4;
    case CAMERA_NOT_WORKING = 5;
    case SPEAKER_ISSUE = 6;
    case MICROPHONE_ISSUE = 7;
    case FACE_ID_NOT_WORKING = 8;
    case TOUCH_NOT_RESPONDING = 9;
    case OVERHEATING = 10;
    case SOFTWARE_ISSUE = 11;
    case NO_SIGNAL = 12;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BROKEN_SCREEN => 'Broken Screen',
            self::BATTERY_ISSUE => 'Battery Issue',
            self::NOT_CHARGING => 'Not Charging',
            self::NO_POWER => 'No Power',
            self::CAMERA_NOT_WORKING => 'Camera Not Working',
            self::SPEAKER_ISSUE => 'Speaker Issue',
            self::MICROPHONE_ISSUE => 'Microphone Issue',
            self::FACE_ID_NOT_WORKING => 'Face ID Not Working',
            self::TOUCH_NOT_RESPONDING => 'Touch Not Responding',
            self::OVERHEATING => 'Overheating',
            self::SOFTWARE_ISSUE => 'Software Issue',
            self::NO_SIGNAL => 'No Signal',
        };
    }
}

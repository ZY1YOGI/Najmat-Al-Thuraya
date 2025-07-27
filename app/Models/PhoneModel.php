<?php

namespace App\Models;

use App\Enums\PhoneColors;
use Illuminate\Database\Eloquent\Model;

use App\Enums\PhoneStorages;

class PhoneModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'model',
        'storages',
        'colors',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'storages' => 'array',
            'colors' => 'array',
        ];
    }

    public function getColorsAttribute($value)
    {
        return collect(json_decode($value))->map(fn($color) => PhoneColors::from($color));
    }

    public function getStoragesAttribute($value)
    {
        return collect(json_decode($value))->map(fn($storage) => PhoneStorages::from($storage));
    }
}

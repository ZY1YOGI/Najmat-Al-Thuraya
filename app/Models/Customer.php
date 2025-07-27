<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'store_name',
        'phones',
        'address',
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
            'phones' => 'array',
        ];
    }

    public function invoices_buy(): HasMany
    {
        return $this->hasMany(related: Phone::class, foreignKey: 'customer_buy_id');
    }

    public function invoices_sell(): HasMany
    {
        return $this->hasMany(related: Phone::class, foreignKey: 'customer_sell_id');
    }
}

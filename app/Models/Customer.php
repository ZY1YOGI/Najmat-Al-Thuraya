<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Enums\PaymentTypes;

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


    public function invoicesBuyCountWithTotal(): Attribute
    {
        return Attribute::get(
            fn() => "{$this->invoices_buy->count()} || EGP {$this->invoices_buy->sum('price')}"
        );
    }

    public function invoicesSellCountWithTotal(): Attribute
    {
        return Attribute::get(
            fn() => "{$this->invoices_sell->count()} || EGP {$this->invoices_sell->sum('price_sell')}"
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(related: Payment::class, foreignKey: 'customer_id');
    }

    public function payments_take_from(): HasMany
    {
        return $this->payments()->where(column: 'payment_type', operator: PaymentTypes::TAKE_FROM);
    }

    public function payments_give_his(): HasMany
    {
        return $this->payments()->where(column: 'payment_type', operator: PaymentTypes::GIVE_HIS);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Enums\PaymentOptions;
use App\Enums\PaymentTypes;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'payment_type',
        'payment_date',
        'amount',
        'payment_option',
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
            'payment_type' => PaymentTypes::class,
            'payment_option' => PaymentOptions::class,
            'payment_date' => 'datetime',
        ];
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(related: Customer::class, foreignKey: 'customer_id');
    }
}

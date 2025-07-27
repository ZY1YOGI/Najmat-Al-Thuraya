<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Enums\InvoiceTypes;
use App\Enums\PhoneColors;
use App\Enums\PhoneStorages;
use App\Enums\PhoneCountries;
use App\Enums\Problems;
use App\Enums\PhoneSim;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'phone_id',
        'imei',
        'color',
        'storage',
        'country',
        'battery',
        'sim_card',
        'problems',
        'price',
        'price_sell',
        'customer_buy_id',
        'customer_sell_id',
        'description',
        'status',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'color' => PhoneColors::class,
            'storage' => PhoneStorages::class,
            'country' => PhoneCountries::class,
            'battery' => 'int',
            'sim_card' => PhoneSim::class,
            'problems' => 'array',
            'price' => 'int',
            'price_sell' => 'int',
            'status' => InvoiceTypes::class,
        ];
    }

    public function phone(): BelongsTo
    {
        return $this->belongsTo(related: PhoneModel::class);
    }

    public function customerBuy(): BelongsTo
    {
        return $this->belongsTo(related: Customer::class, foreignKey: 'customer_buy_id');
    }

    public function customerSell(): BelongsTo
    {
        return $this->belongsTo(related: Customer::class, foreignKey: 'customer_sell_id');
    }

    public function getAllProblemsAttribute()
    {
        return $this->problems ? collect($this->problems)->map(fn($problem) => Problems::from($problem)) : 'N/A';
    }
}

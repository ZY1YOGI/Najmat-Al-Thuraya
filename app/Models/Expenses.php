<?php

namespace App\Models;

use App\Enums\ExpensesPaymentOptions;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'expenses_date',
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
            'expenses_date' => 'date',
            'payment_option' => ExpensesPaymentOptions::class
        ];
    }
}

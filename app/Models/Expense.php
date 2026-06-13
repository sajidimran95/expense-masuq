<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'expense_month',
    'expense_date',
    'sector',
    'description',
    'amount',
    'voucher_no',
    'approval',
])]
class Expense extends Model
{
    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    protected function formattedAmount(): Attribute
    {
        return Attribute::get(fn (): string => '৳ '.number_format((float) $this->amount, 2));
    }
}

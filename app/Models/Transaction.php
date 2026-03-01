<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'budget_id',
        'category_id',
        'type',
        'amount',
        'description',
        'transaction_date',
        'payment_method',
        'is_recurring',
        'recurring_frequency',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'is_recurring' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function category()
    {
        return $this->belongsTo(BudgetCategory::class, 'category_id');
    }}

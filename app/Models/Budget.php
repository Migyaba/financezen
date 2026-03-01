<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'salary_planned',
        'salary_actual',
        'freelance_planned',
        'freelance_actual',
        'notes',
    ];

    protected $casts = [
        'salary_planned' => 'decimal:2',
        'salary_actual' => 'decimal:2',
        'freelance_planned' => 'decimal:2',
        'freelance_actual' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

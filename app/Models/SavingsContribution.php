<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsContribution extends Model
{
    protected $fillable = [
        'savings_goal_id',
        'user_id',
        'amount',
        'contribution_date',
        'notes',
    ];

    protected $casts = [
        'contribution_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goal()
    {
        return $this->belongsTo(SavingsGoal::class, 'savings_goal_id');
    }}

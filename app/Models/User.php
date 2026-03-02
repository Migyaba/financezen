<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Observers\UserObserver;
use App\Observers\UserCreatedObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'currency',
        'monthly_salary',
        'freelance_split',
        'loyer',
        'eau_electricite',
        'internet',
        'nourriture',
        'essence',
        'dette_initiale',
        'objectif_fonds_urgence',
        'trial_ends_at',
        'is_active',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Bootstrap the model and register observers.
     */
    protected static function boot(): void
    {
        parent::boot();
        static::observe(UserObserver::class);
        static::observe(UserCreatedObserver::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'is_active' => 'boolean',
            'monthly_salary' => 'decimal:2',
            'loyer' => 'decimal:2',
            'eau_electricite' => 'decimal:2',
            'internet' => 'decimal:2',
            'nourriture' => 'decimal:2',
            'essence' => 'decimal:2',
            'dette_initiale' => 'decimal:2',
            'objectif_fonds_urgence' => 'decimal:2',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgetCategories()
    {
        return $this->hasMany(BudgetCategory::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function savingsGoals()
    {
        return $this->hasMany(SavingsGoal::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}


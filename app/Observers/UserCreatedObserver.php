<?php

namespace App\Observers;

use App\Models\User;
use App\Models\BudgetCategory;

class UserCreatedObserver
{
    /**
     * Listen to the User created event.
     */
    public function created(User $user): void
    {
        // Create default budget categories for new user
        $defaultCategories = [
            ['name' => 'Salaire', 'type' => 'income', 'color' => '#10b981', 'icon' => 'briefcase'],
            ['name' => 'Loyer', 'type' => 'expense', 'color' => '#f59e0b', 'icon' => 'home'],
            ['name' => 'Nourriture', 'type' => 'expense', 'color' => '#ef4444', 'icon' => 'utensils'],
            ['name' => 'Essence', 'type' => 'expense', 'color' => '#3b82f6', 'icon' => 'fuel'],
            ['name' => 'Internet', 'type' => 'expense', 'color' => '#6366f1', 'icon' => 'globe'],
            ['name' => 'Eau & Électricité', 'type' => 'expense', 'color' => '#0ea5e9', 'icon' => 'zap'],
            ['name' => 'Loisirs', 'type' => 'expense', 'color' => '#8b5cf6', 'icon' => 'gamepad2'],
            ['name' => 'Santé', 'type' => 'expense', 'color' => '#ec4899', 'icon' => 'heart-pulse'],
        ];

        foreach ($defaultCategories as $category) {
            $user->budgetCategories()->create($category);
        }
    }
}

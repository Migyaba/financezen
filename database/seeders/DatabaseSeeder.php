<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@financezen.app'],
            [
                'name' => 'Admin FinanceZen',
                'password' => bcrypt('Admin@2025!'),
                'role' => 'admin',
                'trial_ends_at' => now()->addYears(10),
            ]
        );

        // Default categories (PromptComplet.md)
        $categories = [
            // DÉPENSES
            ['name' => 'Loyer', 'type' => 'expense', 'color' => '#EF4444', 'icon' => 'home'],
            ['name' => 'Eau & Électricité', 'type' => 'expense', 'color' => '#F97316', 'icon' => 'zap'],
            ['name' => 'Internet', 'type' => 'expense', 'color' => '#3B82F6', 'icon' => 'globe'],
            ['name' => 'Nourriture', 'type' => 'expense', 'color' => '#10B981', 'icon' => 'shopping-cart'],
            ['name' => 'Essence', 'type' => 'expense', 'color' => '#EAB308', 'icon' => 'fuel'],
            ['name' => 'Transport', 'type' => 'expense', 'color' => '#06B6D4', 'icon' => 'bus'],
            ['name' => 'Vêtements', 'type' => 'expense', 'color' => '#8B5CF6', 'icon' => 'shirt'],
            ['name' => 'Santé', 'type' => 'expense', 'color' => '#EC4899', 'icon' => 'heart-pulse'],
            ['name' => 'Loisirs', 'type' => 'expense', 'color' => '#6366F1', 'icon' => 'gamepad-2'],
            ['name' => 'Éducation', 'type' => 'expense', 'color' => '#14B8A6', 'icon' => 'graduation-cap'],
            ['name' => 'Téléphone', 'type' => 'expense', 'color' => '#84CC16', 'icon' => 'smartphone'],
            ['name' => 'Sport', 'type' => 'expense', 'color' => '#F59E0B', 'icon' => 'dumbbell'],
            ['name' => 'Cadeaux', 'type' => 'expense', 'color' => '#F472B6', 'icon' => 'gift'],
            ['name' => 'Réparations', 'type' => 'expense', 'color' => '#78716C', 'icon' => 'wrench'],
            ['name' => 'Beauté/Soin', 'type' => 'expense', 'color' => '#D946EF', 'icon' => 'sparkles'],
            ['name' => 'Restaurant', 'type' => 'expense', 'color' => '#FB923C', 'icon' => 'utensils'],
            ['name' => 'Voyage', 'type' => 'expense', 'color' => '#0EA5E9', 'icon' => 'plane'],
            ['name' => 'Divers', 'type' => 'expense', 'color' => '#94A3B8', 'icon' => 'package'],
            // REVENUS
            ['name' => 'Salaire fixe', 'type' => 'income', 'color' => '#10B981', 'icon' => 'briefcase'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#3B82F6', 'icon' => 'laptop'],
            ['name' => 'Investissements', 'type' => 'income', 'color' => '#F59E0B', 'icon' => 'trending-up'],
            ['name' => 'Cadeaux reçus', 'type' => 'income', 'color' => '#EC4899', 'icon' => 'heart'],
            ['name' => 'Autres revenus', 'type' => 'income', 'color' => '#22C55E', 'icon' => 'coins'],
        ];

        foreach ($categories as $i => $cat) {
            \App\Models\BudgetCategory::updateOrCreate(
                ['name' => $cat['name'], 'user_id' => null],
                $cat + ['is_default' => true, 'order_index' => $i]
            );
        }
    }
}

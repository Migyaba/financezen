<?php

namespace App\Observers;

use App\Models\Debt;
use App\Models\SavingsGoal;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // ✅ AUTO-CRÉER/METTRE À JOUR LA DETTE si dette_initiale change
        if ($user->wasChanged('dette_initiale')) {
            $this->syncDebt($user);
        }

        // ✅ AUTO-CRÉER/METTRE À JOUR LE FONDS D'URGENCE si objectif_fonds_urgence change
        if ($user->wasChanged('objectif_fonds_urgence')) {
            $this->syncSavingsGoal($user);
        }
    }

    /**
     * Synchroniser la Debt avec la valeur dette_initiale
     */
    private function syncDebt(User $user): void
    {
        $debtAmount = (float)($user->dette_initiale ?? 0);

        if ($debtAmount > 0) {
            // Chercher ou créer une dette auto
            $autoDebt = $user->debts()
                ->where('name', 'Dette Initiale (Auto)')
                ->first();

            if (!$autoDebt) {
                // Créer une nouvelle dette
                Debt::create([
                    'user_id' => $user->id,
                    'name' => 'Dette Initiale (Auto)',
                    'description' => 'Dette créée automatiquement depuis vos paramètres',
                    'initial_amount' => $debtAmount,
                    'current_amount' => $debtAmount,
                    'monthly_payment' => 0,
                    'interest_rate' => 0,
                    'creditor' => 'Vous-même',
                    'status' => 'active',
                    'color' => '#EF4444',
                ]);
            } else {
                // Mettre à jour le montant si différent
                $oldAmount = (float)$autoDebt->initial_amount;
                if ($oldAmount !== $debtAmount) {
                    $diff = $debtAmount - $oldAmount;
                    $autoDebt->update([
                        'initial_amount' => $debtAmount,
                        'current_amount' => max(0, (float)$autoDebt->current_amount + $diff),
                    ]);
                }
            }
        } else {
            // Si dette_initiale devient 0, archiver la dette auto
            $user->debts()
                ->where('name', 'Dette Initiale (Auto)')
                ->update(['status' => 'paid']);
        }
    }

    /**
     * Synchroniser le SavingsGoal avec la valeur objectif_fonds_urgence
     */
    private function syncSavingsGoal(User $user): void
    {
        $targetAmount = (float)($user->objectif_fonds_urgence ?? 0);

        if ($targetAmount > 0) {
            // Chercher ou créer un objectif d'épargne auto
            $autoGoal = $user->savingsGoals()
                ->where('name', 'Fonds d\'Urgence (Auto)')
                ->first();

            if (!$autoGoal) {
                // Créer un nouvel objectif
                SavingsGoal::create([
                    'user_id' => $user->id,
                    'name' => 'Fonds d\'Urgence (Auto)',
                    'description' => 'Fonds d\'urgence créé automatiquement depuis vos paramètres',
                    'target_amount' => $targetAmount,
                    'current_amount' => 0,
                    'monthly_target' => $targetAmount / 48, // 4 ans
                    'target_date' => now()->addYears(4),
                    'type' => 'emergency_fund',
                    'status' => 'active',
                    'color' => '#10B981',
                ]);
            } else {
                // Mettre à jour le montant si différent
                $oldAmount = (float)$autoGoal->target_amount;
                if ($oldAmount !== $targetAmount) {
                    $autoGoal->update([
                        'target_amount' => $targetAmount,
                        'monthly_target' => $targetAmount / 48,
                    ]);
                }
            }
        } else {
            // Si objectif_fonds_urgence devient 0, archiver l'objectif auto
            $user->savingsGoals()
                ->where('name', 'Fonds d\'Urgence (Auto)')
                ->update(['status' => 'paused']);
        }
    }
}

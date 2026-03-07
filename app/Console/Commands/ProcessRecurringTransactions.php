<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-recurring-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les transactions récurrentes échues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();

        $recurringTransactions = \App\Models\Transaction::where('is_recurring', true)
            ->whereNotNull('next_recurring_date')
            ->whereDate('next_recurring_date', '<=', $today)
            ->get();

        $count = 0;

        foreach ($recurringTransactions as $transaction) {
            // Duplicate the transaction
            $newTransaction = $transaction->replicate();
            $newTransaction->is_recurring = true;
            $newTransaction->transaction_date = $transaction->next_recurring_date;
            
            // Calculate next recurring date based on frequency
            $date = \Carbon\Carbon::parse($newTransaction->transaction_date);
            $newTransaction->next_recurring_date = match ($transaction->recurring_frequency) {
                'daily' => $date->addDay(),
                'weekly' => $date->addWeek(),
                'monthly' => $date->addMonth(),
                'yearly' => $date->addYear(),
                default => $date->addMonth(),
            };
            
            $newTransaction->parent_id = $transaction->id;
            $newTransaction->save();

            // The old one is no longer the active recurring template
            $transaction->is_recurring = false;
            $transaction->next_recurring_date = null;
            $transaction->save();

            $count++;
        }

        $this->info("Processus terminé : {$count} transaction(s) récurrente(s) générée(s).");
    }
}

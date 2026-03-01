<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();

        // === KPI 1: Solde mensuel ===
        $incomeThisMonth = $user->transactions()
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('type', 'income')
            ->sum('amount');
        $expenseThisMonth = $user->transactions()
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('type', 'expense')
            ->sum('amount');
        $monthlyBalance = $incomeThisMonth - $expenseThisMonth;

        // Previous month for trend
        $prevMonth = $now->copy()->subMonth();
        $prevIncome = $user->transactions()
            ->whereMonth('transaction_date', $prevMonth->month)
            ->whereYear('transaction_date', $prevMonth->year)
            ->where('type', 'income')->sum('amount');
        $prevExpense = $user->transactions()
            ->whereMonth('transaction_date', $prevMonth->month)
            ->whereYear('transaction_date', $prevMonth->year)
            ->where('type', 'expense')->sum('amount');
        $prevBalance = $prevIncome - $prevExpense;
        $balanceTrend = $prevBalance != 0 ? round((($monthlyBalance - $prevBalance) / abs($prevBalance)) * 100) : 0;

        // === KPI 2: Dette restante ===
        $totalDebt = $user->debts()->where('status', 'active')->sum('current_amount');
        $debtPaidThisMonth = $user->transactions()
            ->whereMonth('transaction_date', $now->month)
            ->where('type', 'expense')
            ->sum('amount'); // Simplified

        // === KPI 3: Épargne totale ===
        $totalSavings = $user->savingsGoals()->sum('current_amount');

        // === KPI 4: Fonds urgence ===
        $emergencyFund = $user->savingsGoals()->where('type', 'emergency_fund')->first();
        $emergencyFundPercent = $emergencyFund && $emergencyFund->target_amount > 0
            ? round(($emergencyFund->current_amount / $emergencyFund->target_amount) * 100)
            : 0;

        // === Feux tricolores (dynamiques) ===
        $balanceStatus = $monthlyBalance > 0 ? 'success' : ($monthlyBalance == 0 ? 'warning' : 'danger');
        $balanceMessage = $monthlyBalance > 0 ? 'Excellent' : ($monthlyBalance == 0 ? 'Neutre' : 'Attention');
        $balanceDesc = $monthlyBalance > 0
            ? 'Vous dépensez moins que ce que vous gagnez.'
            : ($monthlyBalance == 0 ? 'Votre solde est à l\'équilibre.' : 'Vos dépenses dépassent vos revenus.');

        $savingsStatus = $totalSavings > 0 ? ($emergencyFundPercent >= 80 ? 'success' : 'warning') : 'danger';
        $savingsMessage = $totalSavings > 0 ? ($emergencyFundPercent >= 80 ? 'Excellent' : 'En progrès') : 'À démarrer';
        $savingsDesc = $totalSavings > 0 ? 'Continuez vos efforts d\'épargne ce mois-ci.' : 'Commencez à épargner dès maintenant.';

        $debtStatus = $totalDebt == 0 ? 'success' : ($totalDebt < 100000 ? 'warning' : 'danger');
        $debtMessage = $totalDebt == 0 ? 'Aucune dette' : ($totalDebt < 100000 ? 'En bonne voie' : 'Renforcez les paiements');
        $debtDesc = $totalDebt == 0 ? 'Vous n\'avez aucune dette active.' : 'Vos remboursements sont en cours.';

        // === Graphique: 6 derniers mois ===
        $chartLabels = [];
        $chartIncomes = [];
        $chartExpenses = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $chartLabels[] = $date->translatedFormat('M');
            $chartIncomes[] = $user->transactions()
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->where('type', 'income')->sum('amount');
            $chartExpenses[] = $user->transactions()
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->where('type', 'expense')->sum('amount');
        }

        // === Camembert: Répartition dépenses du mois ===
        $expensesByCategory = $user->transactions()
            ->with('category')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // === Top 5 dernières transactions ===
        $recentTransactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        // === Objectifs d'épargne ===
        $savingsGoals = $user->savingsGoals()->where('status', 'active')->get();

        // === Prochains paiements de dette ===
        $upcomingDebts = $user->debts()
            ->where('status', 'active')
            ->where('monthly_payment', '>', 0)
            ->orderBy('due_date')
            ->limit(3)
            ->get();

        // === Alerte trial/abonnement ===
        $trialDaysLeft = null;
        $subscriptionExpired = false;
        if ($user->trial_ends_at) {
            if ($user->trial_ends_at->isFuture()) {
                $trialDaysLeft = now()->diffInDays($user->trial_ends_at);
            } else {
                $activeSubscription = $user->subscriptions()
                    ->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->exists();
                if (!$activeSubscription) {
                    $subscriptionExpired = true;
                }
            }
        }

        return view('user.dashboard.index', compact(
            'monthlyBalance', 'totalDebt', 'totalSavings', 'emergencyFundPercent',
            'balanceTrend', 'incomeThisMonth', 'expenseThisMonth',
            'balanceStatus', 'balanceMessage', 'balanceDesc',
            'savingsStatus', 'savingsMessage', 'savingsDesc',
            'debtStatus', 'debtMessage', 'debtDesc',
            'chartLabels', 'chartIncomes', 'chartExpenses',
            'expensesByCategory', 'recentTransactions', 'savingsGoals', 'upcomingDebts',
            'trialDaysLeft', 'subscriptionExpired'
        ));
    }
}

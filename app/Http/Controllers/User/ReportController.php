<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', '6'); // default 6 months for chart

        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfMonth();

        // Fetch all relevant transactions for the chart (last 6 months)
        $monthsChartData = $user->transactions()
            ->where('transaction_date', '>=', now()->subMonths(5)->startOfMonth())
            ->get();

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $currentDate = now()->subMonths($i);
            $mCode = $currentDate->format('Y-m');
            
            $periodTx = $monthsChartData->filter(fn($t) => $t->transaction_date->format('Y-m') === $mCode);
            
            $inc = $periodTx->where('type', 'income')->sum('amount');
            $exp = $periodTx->whereIn('type', ['expense', 'debt_payment', 'savings'])->sum('amount');

            $months->push([
                'label' => $currentDate->translatedFormat('M Y'),
                'income' => (float)$inc,
                'expense' => (float)$exp,
                'balance' => (float)($inc - $exp),
            ]);
        }

        // Transactions for the selected period
        $periodTransactions = $user->transactions()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        $currentIncome = $periodTransactions->where('type', 'income')->sum('amount');
        $currentExpense = $periodTransactions->whereIn('type', ['expense', 'debt_payment', 'savings'])->sum('amount');

        // Comparison with Previous Period
        $durationInDays = $startDate->diffInDays($endDate) + 1;
        $prevStartDate = $startDate->copy()->subDays($durationInDays);
        $prevEndDate = $endDate->copy()->subDays($durationInDays);

        $prevTransactions = $user->transactions()
            ->whereBetween('transaction_date', [$prevStartDate, $prevEndDate])
            ->get();

        $prevIncome = $prevTransactions->where('type', 'income')->sum('amount');
        $prevExpense = $prevTransactions->whereIn('type', ['expense', 'debt_payment', 'savings'])->sum('amount');

        $incomeChange = $prevIncome > 0 ? round((($currentIncome - $prevIncome) / $prevIncome) * 100) : ($currentIncome > 0 ? 100 : 0);
        $expenseChange = $prevExpense > 0 ? round((($currentExpense - $prevExpense) / $prevExpense) * 100) : ($currentExpense > 0 ? 100 : 0);

        // Pie Chart & Top Categories (based on period transactions)
        $expenseGroups = $periodTransactions->whereIn('type', ['expense', 'debt_payment', 'savings'])
            ->groupBy(function($t) {
                return ($t->category_id ?? 'null') . '_' . $t->type;
            });

        $categoriesData = collect();
        foreach ($expenseGroups as $key => $groupTx) {
            $first = $groupTx->first();
            $categoriesData->push([
                'name' => $first->category->name ?? ($first->type === 'debt_payment' ? 'Dettes' : ($first->type === 'savings' ? 'Épargne' : 'Autre')),
                'color' => $first->category->color ?? ($first->type === 'debt_payment' ? '#F43F5E' : ($first->type === 'savings' ? '#10B981' : '#64748b')),
                'icon' => $first->category->icon ?? ($first->type === 'debt_payment' ? 'credit-card' : ($first->type === 'savings' ? 'piggy-bank' : 'tag')),
                'type' => $first->type,
                'total' => (float)$groupTx->sum('amount')
            ]);
        }

        $topCategories = $categoriesData->sortByDesc('total')->take(5);
        
        $pieChartData = $categoriesData->map(fn($item) => [
            'label' => $item['name'],
            'value' => $item['total'],
            'color' => $item['color']
        ]);

        $debtProgress = 0;
        $savingsProgress = 0;
        $debtsTotal = collect(['initial' => 0, 'current' => 0]);
        $savingsTotal = collect(['target' => 0, 'current' => 0]);

        $activeDebts = $user->debts;
        if ($activeDebts->isNotEmpty()) {
            $debtsTotal['initial'] = (float)$activeDebts->sum('initial_amount');
            $debtsTotal['current'] = (float)$activeDebts->sum('current_amount');
            $debtProgress = $debtsTotal['initial'] > 0 ? round((($debtsTotal['initial'] - $debtsTotal['current']) / $debtsTotal['initial']) * 100) : 0;
        }
        
        $activeSavings = $user->savingsGoals;
        if ($activeSavings->isNotEmpty()) {
            $savingsTotal['target'] = (float)$activeSavings->sum('target_amount');
            $savingsTotal['current'] = (float)$activeSavings->sum('current_amount');
            $savingsProgress = $savingsTotal['target'] > 0 ? round(($savingsTotal['current'] / $savingsTotal['target']) * 100) : 0;
        }

        return view('user.reports.index', compact(
            'months', 'topCategories', 'period', 'startDate', 'endDate',
            'currentIncome', 'currentExpense', 'incomeChange', 'expenseChange',
            'pieChartData', 'debtProgress', 'savingsProgress', 'debtsTotal', 'savingsTotal'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfMonth();

        $transactions = auth()->user()->transactions()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest('transaction_date')
            ->get();

        $csvContent = "\xEF\xBB\xBFDate;Catégorie;Type;Montant;Description;Mode\n"; // UTF8 BOM for Excel
        foreach ($transactions as $t) {
            $csvContent .= $t->transaction_date->format('d/m/Y') . ';'
                . ($t->category->name ?? '-') . ';'
                . ($t->type === 'income' ? 'Revenu' : 'Dépense') . ';'
                . $t->amount . ';'
                . str_replace(';', ',', $t->description ?? '-') . ';'
                . $t->payment_method . "\n";
        }

        $filename = "transactions_export_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfMonth();

        $transactions = $user->transactions()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest('transaction_date')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        
        $categoriesData = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($row) {
                return [
                    'name' => $row->first()->category->name ?? 'Non catégorisé',
                    'total' => $row->sum('amount')
                ];
            })->sortByDesc('total')->take(10);

        // Load DOMPDF facade using a specialized simple blade view to avoid Alpine/Tailwind issues
        $pdf = Pdf::loadView('user.reports.pdf', compact('user', 'startDate', 'endDate', 'transactions', 'income', 'expense', 'categoriesData'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("rapport_financezen_{$startDate->format('Ymd')}.pdf");
    }
}

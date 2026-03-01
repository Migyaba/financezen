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

        // 1. Monthly Summary Line Chart Data
        $months = collect();
        if ($period !== 'custom') {
            for ($i = $period - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $income = $user->transactions()
                    ->whereMonth('transaction_date', $date->month)
                    ->whereYear('transaction_date', $date->year)
                    ->where('type', 'income')
                    ->sum('amount');
                $expense = $user->transactions()
                    ->whereMonth('transaction_date', $date->month)
                    ->whereYear('transaction_date', $date->year)
                    ->where('type', 'expense')
                    ->sum('amount');

                $months->push([
                    'label' => $date->translatedFormat('M Y'),
                    'income' => $income,
                    'expense' => $expense,
                    'balance' => $income - $expense,
                ]);
            }
        }

        // 2. Data for the selected period (or current month)
        $currentIncome = $user->transactions()->whereBetween('transaction_date', [$startDate, $endDate])->where('type', 'income')->sum('amount');
        $currentExpense = $user->transactions()->whereBetween('transaction_date', [$startDate, $endDate])->where('type', 'expense')->sum('amount');

        // 3. Comparison with Previous Period (Same duration)
        $durationInDays = $startDate->diffInDays($endDate) + 1;
        $prevStartDate = $startDate->copy()->subDays($durationInDays);
        $prevEndDate = $endDate->copy()->subDays($durationInDays);

        $prevIncome = $user->transactions()->whereBetween('transaction_date', [$prevStartDate, $prevEndDate])->where('type', 'income')->sum('amount');
        $prevExpense = $user->transactions()->whereBetween('transaction_date', [$prevStartDate, $prevEndDate])->where('type', 'expense')->sum('amount');

        $incomeChange = $prevIncome > 0 ? round((($currentIncome - $prevIncome) / $prevIncome) * 100) : ($currentIncome > 0 ? 100 : 0);
        $expenseChange = $prevExpense > 0 ? round((($currentExpense - $prevExpense) / $prevExpense) * 100) : ($currentExpense > 0 ? 100 : 0);

        // 4. Pie Chart & Top Categories (based on selected period)
        $categoriesData = $user->transactions()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        $topCategories = $categoriesData->take(5);
        $pieChartData = $categoriesData->map(fn($item) => [
            'label' => $item->category->name ?? 'Non catégorisé',
            'value' => $item->total,
            'color' => $item->category->color ?? '#94A3B8'
        ]);

        // 5. Debt & Savings Progress
        $debtsTotal = collect(['initial' => 0, 'current' => 0]);
        $savingsTotal = collect(['target' => 0, 'current' => 0]);

        if ($user->debts) {
            $debtsTotal['initial'] = $user->debts->sum('initial_amount');
            $debtsTotal['current'] = $user->debts->sum('current_amount');
        }
        
        if ($user->savingsGoals) {
            $savingsTotal['target'] = $user->savingsGoals->sum('target_amount');
            $savingsTotal['current'] = $user->savingsGoals->sum('current_amount');
        }

        $debtProgress = $debtsTotal['initial'] > 0 ? round((($debtsTotal['initial'] - $debtsTotal['current']) / $debtsTotal['initial']) * 100) : 0;
        $savingsProgress = $savingsTotal['target'] > 0 ? round(($savingsTotal['current'] / $savingsTotal['target']) * 100) : 0;

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

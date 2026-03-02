<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * GET /api/user/budget/{year}/{month}
     */
    public function show($year, $month, Request $request)
    {
        $budget = auth()->user()->budgets()
            ->where('year', $year)
            ->where('month', $month)
            ->firstOrFail();

        $transactions = auth()->user()->transactions()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->get();

        return response()->json([
            'month' => $month,
            'year' => $year,
            'salary_planned' => (float)$budget->salary_planned,
            'salary_actual' => (float)$budget->salary_actual,
            'freelance_planned' => (float)$budget->freelance_planned,
            'freelance_actual' => (float)$budget->freelance_actual,
            'total_income' => (float)$transactions->where('type', 'income')->sum('amount'),
            'total_expense' => (float)$transactions->where('type', 'expense')->sum('amount'),
            'monthly_balance' => (float)(
                $transactions->where('type', 'income')->sum('amount') -
                $transactions->where('type', 'expense')->sum('amount')
            ),
        ]);
    }

    /**
     * GET /api/user/budget/{year}/{month}/summary
     */
    public function summary($year, $month, Request $request)
    {
        $budget = auth()->user()->budgets()
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$budget) {
            return response()->json([
                'message' => 'Budget not found'
            ], 404);
        }

        $transactions = auth()->user()->transactions()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        return response()->json([
            'month' => $month,
            'year' => $year,
            'total_income' => (float)$income,
            'total_expense' => (float)$expense,
            'monthly_balance' => (float)($income - $expense),
            'net_balance' => (float)($income - $expense),
            'expenses_by_category' => $transactions
                ->where('type', 'expense')
                ->groupBy('category_id')
                ->map(fn($items) => (float)$items->sum('amount'))
                ->toArray(),
        ]);
    }

    /**
     * GET /api/user/budget-categories?type=expense|income
     */
    public function categories(Request $request)
    {
        $query = BudgetCategory::where('user_id', auth()->id())
            ->orWhere('is_default', true);

        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        return response()->json([
            'data' => $query->orderBy('order_index')->get()
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * GET /api/user/transactions?per_page=20
     */
    public function index(Request $request)
    {
        $query = auth()->user()->transactions()->with('category');

        $per_page = $request->get('per_page', 20);
        $transactions = $query->latest('transaction_date')->paginate($per_page);

        return response()->json($transactions);
    }

    /**
     * GET /api/user/transactions/summary?month=3&year=2026
     */
    public function summary(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $transactions = auth()->user()->transactions()
            ->with('category')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->whereIn('type', ['expense', 'debt_payment', 'savings'])->sum('amount');

        $byCategory = [];
        foreach ($transactions->groupBy('category_id') as $catId => $items) {
            $category = $items->first()->category;
            if ($category) {
                $byCategory[$category->name] = (float)$items->sum('amount');
            }
        }

        return response()->json([
            'month' => $month,
            'year' => $year,
            'total_income' => (float)$income,
            'total_expense' => (float)$expense,
            'net_balance' => (float)($income - $expense),
            'by_category' => $byCategory,
            'transaction_count' => $transactions->count(),
        ]);
    }
}

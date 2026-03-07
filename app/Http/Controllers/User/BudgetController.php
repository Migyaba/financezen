<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $budget = auth()->user()->budgets()
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        // AUTO-FILL: Créer ou mettre à jour les dépenses fixes pré-remplies depuis le profil
        if (!$budget) {
            $budget = auth()->user()->budgets()->create([
                'month' => $month, 'year' => $year,
                'salary_planned' => auth()->user()->monthly_salary,
                'salary_actual' => 0, 'freelance_planned' => 0, 'freelance_actual' => 0,
            ]);
        }

        // On s'assure que le salaire prévu est synchronisé si toujours à 0
        if ($budget->salary_planned == 0 && auth()->user()->monthly_salary > 0) {
            $budget->update(['salary_planned' => auth()->user()->monthly_salary]);
        }

        // AUTO-FILL: Préremplir les revenus fixes depuis le profil
        $fixedIncomes = [
            'Salaire fixe' => auth()->user()->monthly_salary,
        ];

        foreach ($fixedIncomes as $categoryName => $amount) {
            if ($amount > 0) {
                $category = BudgetCategory::where('name', $categoryName)
                    ->where(function($q) { $q->where('user_id', auth()->id())->orWhere('is_default', true); })
                    ->where('type', 'income')
                    ->orderByRaw('user_id IS NULL ASC') // Prioritize user-specific categories
                    ->first();
                
                if ($category) {
                    $item = $budget->items()->where('category_id', $category->id)->first();
                    if (!$item || (float)$item->amount_planned == 0) {
                        $budget->items()->updateOrCreate(
                            ['category_id' => $category->id],
                            ['amount_planned' => $amount]
                        );
                    }
                }
            }
        }

        // AUTO-FILL: Préremplir les dépenses fixes depuis le profil
        $fixedExpenses = [
            'Loyer' => auth()->user()->loyer,
            'Eau & Électricité' => auth()->user()->eau_electricite,
            'Internet' => auth()->user()->internet,
            'Nourriture' => auth()->user()->nourriture,
            'Essence' => auth()->user()->essence,
        ];

        foreach ($fixedExpenses as $categoryName => $amount) {
            if ($amount > 0) {
                $category = BudgetCategory::where('name', $categoryName)
                    ->where(function($q) { $q->where('user_id', auth()->id())->orWhere('is_default', true); })
                    ->orderByRaw('user_id IS NULL ASC') // Prioritize user-specific categories
                    ->first();
                
                if ($category) {
                    $item = $budget->items()->where('category_id', $category->id)->first();
                    if (!$item || (float)$item->amount_planned == 0) {
                        $budget->items()->updateOrCreate(
                            ['category_id' => $category->id],
                            ['amount_planned' => $amount]
                        );
                    }
                }
            }
        }

        $categories = BudgetCategory::where('user_id', auth()->id())
            ->orWhere('is_default', true)
            ->orderBy('type')
            ->orderBy('order_index')
            ->get();

        // Monthly transactions for this budget period
        $transactions = auth()->user()->transactions()
            ->with('category')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->get();

        $incomeTotal = $transactions->where('type', 'income')->sum('amount');
        $expenseTotal = $transactions->whereIn('type', ['expense', 'debt_payment', 'savings'])->sum('amount');

        return view('user.budget.index', compact(
            'budget', 'categories', 'transactions',
            'month', 'year', 'incomeTotal', 'expenseTotal'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'salary_planned' => 'nullable|numeric|min:0',
            'freelance_planned' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->budgets()->updateOrCreate(
            ['month' => $validated['month'], 'year' => $validated['year']],
            $validated
        );

        return back()->with('success', 'Budget enregistré avec succès.');
    }

    public function show($year, $month)
    {
        return redirect()->route('budget.index', ['year' => $year, 'month' => $month]);
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'salary_planned' => 'nullable|numeric|min:0',
            'salary_actual' => 'nullable|numeric|min:0',
            'freelance_planned' => 'nullable|numeric|min:0',
            'freelance_actual' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);

        return back()->with('success', 'Budget mis à jour.');
    }

    public function updateItem(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'category_id' => 'required|exists:budget_categories,id',
            'amount_planned' => 'required|numeric|min:0',
        ]);

        $budget = auth()->user()->budgets()->firstOrCreate(
            ['month' => $validated['month'], 'year' => $validated['year']]
        );

        $budget->items()->updateOrCreate(
            ['category_id' => $validated['category_id']],
            ['amount_planned' => $validated['amount_planned']]
        );

        return back()->with('success', 'Montant prévu mis à jour.');
    }
}

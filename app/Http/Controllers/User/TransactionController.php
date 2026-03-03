<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->transactions()->with('category');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('month')) {
            $monthParts = explode('-', $request->month);
            if (count($monthParts) == 2) {
                $query->whereYear('transaction_date', $monthParts[0])
                      ->whereMonth('transaction_date', $monthParts[1]);
            }
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('description', 'like', "%$s%")
                  ->orWhere('notes', 'like', "%$s%");
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(20);
        $transactionMonths = auth()->user()->transactions()
            ->selectRaw("TO_CHAR(transaction_date, 'YYYY-MM') as month")
            ->distinct()
            ->orderByDesc('month')
            ->pluck('month');

        $categories = \App\Models\BudgetCategory::where('user_id', auth()->id())
            ->orWhere('is_default', true)
            ->get();

        return view('user.transactions.index', compact('transactions', 'categories', 'transactionMonths'));
    }

    public function store(Request $request)
    {
        // Debug: Log qui está enregistrant une transaction
        \Log::info('Storing transaction for user: ' . auth()->id());

        $validated = $request->validate([
            'category_id' => 'required', // Can be 'new' or an ID
            'new_category_name' => 'required_if:category_id,new|nullable|string|max:255',
            'type' => 'required|in:income,expense,debt_payment,savings',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|in:cash,mobile_money,card,transfer,other',
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|string|in:daily,weekly,monthly,yearly',
            'notes' => 'nullable|string',
        ]);

        // Handle dynamic category creation
        if ($validated['category_id'] === 'new') {
            // Budget categories can only be 'income' or 'expense'
            // Map transaction types to budget category types
            $categoryType = $validated['type'] === 'income' ? 'income' : 'expense';
            
            $newCat = auth()->user()->budgetCategories()->create([
                'name' => $validated['new_category_name'],
                'type' => $categoryType,
                'color' => '#' . substr(md5(rand()), 0, 6)
            ]);
            $validated['category_id'] = $newCat->id;
        }

        unset($validated['new_category_name']);
        
        $validated['is_recurring'] = $request->has('is_recurring');

        $transaction = auth()->user()->transactions()->create($validated);
        
        \Log::info('Transaction created: ID = ' . $transaction->id . ', User = ' . $transaction->user_id);

        return back()->with('success', 'Transaction enregistrée avec succès.');
    }

    public function update(Request $request, \App\Models\Transaction $transaction)
    {
        Gate::authorize('update', $transaction);

        $validated = $request->validate([
            'category_id' => 'required|exists:budget_categories,id',
            'type' => 'required|in:income,expense,debt_payment,savings',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|in:cash,mobile_money,card,transfer,other',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return back()->with('success', 'Transaction mise à jour.');
    }

    public function destroy(\App\Models\Transaction $transaction)
    {
        Gate::authorize('delete', $transaction);
        $transaction->delete();
        return back()->with('success', 'Transaction supprimée.');
    }

    public function export(Request $request)
    {
        $transactions = auth()->user()->transactions()->with('category')->latest('transaction_date')->get();
        
        $csvData = "Date,Type,Categorie,Montant,Methode,Description\n";
        foreach ($transactions as $tx) {
            $date = $tx->transaction_date->format('Y-m-d');
            $type = $tx->type;
            $cat = $tx->category->name ?? '';
            $amount = $tx->amount;
            $method = $tx->payment_method;
            $desc = str_replace('"', '""', $tx->description ?? '');
            
            $csvData .= "$date,$type,\"$cat\",$amount,$method,\"$desc\"\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="transactions_financezen.csv"');
    }
}

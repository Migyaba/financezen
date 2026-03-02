<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        $debts = auth()->user()->debts()->withSum('payments', 'amount')->get();
        $totalInitial = $debts->sum('initial_amount');
        $totalCurrent = $debts->sum('current_amount');
        $totalPaid = $totalInitial - $totalCurrent;
        $percentPaid = $totalInitial > 0 ? round(($totalPaid / $totalInitial) * 100) : 0;

        return view('user.debts.index', compact('debts', 'totalInitial', 'totalCurrent', 'totalPaid', 'percentPaid'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_amount' => 'required|numeric|min:0',
            'monthly_payment' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'creditor' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['current_amount'] = $validated['initial_amount'];
        $validated['status'] = 'active';

        auth()->user()->debts()->create($validated);

        return back()->with('success', 'Dette ajoutée avec succès.');
    }

    public function show(Debt $debt)
    {
        $debt->load(['payments' => fn($q) => $q->orderBy('payment_date')]);

        // Calculate cumulative debt amount over time for chart
        $startDate = $debt->created_at ?? now();
        $chartData = collect([
            ['date' => $startDate->format('d/m/Y'), 'amount' => (float)$debt->initial_amount]
        ]);

        $current = $debt->initial_amount;
        foreach ($debt->payments as $payment) {
            $current -= $payment->amount;
            $chartData->push([
                'date' => $payment->payment_date->format('d/m/Y'),
                'amount' => max(0, $current)
            ]);
        }

        return view('user.debts.show', compact('debt', 'chartData'));
    }

    public function update(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_payment' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'creditor' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:active,paid,paused',
        ]);

        $debt->update($validated);

        return back()->with('success', 'Dette mise à jour.');
    }

    public function addPayment(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'source' => 'required|in:salary,freelance,other',
            'notes' => 'nullable|string',
        ]);

        $validated['debt_id'] = $debt->id;
        $validated['user_id'] = auth()->id();

        DebtPayment::create($validated);

        // Update current amount
        $debt->current_amount = max(0, $debt->current_amount - $validated['amount']);
        if ($debt->current_amount <= 0) {
            $debt->status = 'paid';
        }
        $debt->save();

        return back()->with('success', 'Paiement enregistré.');
    }
}

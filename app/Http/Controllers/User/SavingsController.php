<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SavingsGoal;
use App\Models\SavingsContribution;
use Illuminate\Http\Request;

class SavingsController extends Controller
{
    public function index()
    {
        $goals = auth()->user()->savingsGoals()->withSum('contributions', 'amount')->get();
        $totalSaved = $goals->sum('current_amount');
        $activeGoals = $goals->where('status', 'active')->count();
        $achievedGoals = $goals->where('status', 'achieved')->count();

        return view('user.savings.index', compact('goals', 'totalSaved', 'activeGoals', 'achievedGoals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1',
            'monthly_target' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
            'type' => 'required|in:emergency_fund,investment,project,other',
            'icon' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['status'] = 'active';
        $validated['current_amount'] = 0;

        auth()->user()->savingsGoals()->create($validated);

        return back()->with('success', 'Objectif d\'épargne créé avec succès.');
    }

    public function show(SavingsGoal $saving)
    {
        $saving->load(['contributions' => fn($q) => $q->orderBy('contribution_date')]);

        $startDate = $saving->created_at ?? now();
        $chartData = collect([
            ['date' => $startDate->format('d/m/Y'), 'amount' => 0]
        ]);

        $current = 0;
        foreach ($saving->contributions as $contribution) {
            $current += $contribution->amount;
            $chartData->push([
                'date' => $contribution->contribution_date->format('d/m/Y'),
                'amount' => $current
            ]);
        }

        return view('user.savings.show', compact('saving', 'chartData'));
    }

    public function addContribution(Request $request, SavingsGoal $savingsGoal)
    {
        $saving = $savingsGoal;
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'contribution_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['savings_goal_id'] = $saving->id;
        $validated['user_id'] = auth()->id();

        SavingsContribution::create($validated);

        // Update current amount
        $saving->current_amount += $validated['amount'];
        if ($saving->current_amount >= $saving->target_amount) {
            $saving->status = 'achieved';
        }
        $saving->save();

        // Créer une transaction automatiquement
        $category = auth()->user()->budgetCategories()->firstOrCreate(
            ['name' => 'Épargne Objectifs', 'type' => 'expense'],
            ['color' => '#10b981', 'icon' => 'piggy-bank']
        );

        auth()->user()->transactions()->create([
            'category_id' => $category->id,
            'type' => 'savings',
            'amount' => $validated['amount'],
            'description' => 'Contribution épargne: ' . $saving->name,
            'transaction_date' => $validated['contribution_date'],
            'payment_method' => 'other',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Contribution enregistrée.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('phone', 'like', "%$s%"));
        }
        if ($request->filled('status')) {
            match($request->status) {
                'trial' => $query->where('trial_ends_at', '>', now())->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')),
                'active' => $query->whereHas('subscriptions', fn($q) => $q->where('status', 'active')->where('ends_at', '>', now())),
                'expired' => $query->where('trial_ends_at', '<', now())->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')->where('ends_at', '>', now())),
                'inactive' => $query->where('is_active', false),
                default => null,
            };
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['subscriptions', 'payments', 'transactions', 'debts', 'savingsGoals']);
        $stats = [
            'transactions' => $user->transactions()->count(),
            'budgets' => $user->budgets()->count(),
            'debts' => $user->debts()->count(),
            'savings' => $user->savingsGoals()->count(),
        ];
        return view('admin.users.show', compact('user', 'stats'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);
        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    public function extendSubscription(Request $request, User $user)
    {
        $days = $request->validate(['days' => 'required|integer|min:1'])['days'];
        $sub = $user->subscriptions()->where('status', 'active')->latest()->first();

        if ($sub) {
            $sub->update(['ends_at' => $sub->ends_at->addDays($days)]);
        } else {
            $user->subscriptions()->create([
                'plan' => 'manual',
                'status' => 'active',
                'amount' => 0,
                'currency' => 'FCFA',
                'starts_at' => now(),
                'ends_at' => now()->addDays($days),
                'payment_method' => 'free',
            ]);
        }

        return back()->with('success', "Abonnement prolongé de $days jours.");
    }
}

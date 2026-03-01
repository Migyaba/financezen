<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with('subscriptions');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'trial') {
                $query->where('trial_ends_at', '>', now())->whereDoesntHave('subscriptions', function($q) {
                    $q->where('status', 'active');
                });
            } elseif ($status === 'active') {
                $query->whereHas('subscriptions', function($q) {
                    $q->where('status', 'active')->where('ends_at', '>', now());
                });
            } elseif ($status === 'expired') {
                $query->whereHas('subscriptions', function($q) {
                    $q->where('status', '!=', 'active')->orWhere('ends_at', '<', now());
                })->orWhere(function($q) {
                    $q->where('trial_ends_at', '<', now())->whereDoesntHave('subscriptions', function($s) {
                        $s->where('status', 'active')->where('ends_at', '>', now());
                    });
                });
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['subscriptions', 'payments']);
        
        $metrics = [
            'transactions_count' => $user->transactions()->count(),
            'budgets_count' => $user->budgets()->count(),
            'debts_count' => $user->debts()->count(),
            'savings_count' => $user->savingsGoals()->count(),
        ];

        return view('admin.users.show', compact('user', 'metrics'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'role' => 'in:user,admin',
        ]);

        $user->update([
            'is_active' => $request->has('is_active'),
            'role' => $request->input('role', $user->role),
        ]);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function extendSubscription(Request $request, User $user)
    {
        $request->validate(['days' => 'required|integer|min:1']);
        
        $activeSub = $user->subscriptions()->where('status', 'active')->where('ends_at', '>', now())->first();
        
        if ($activeSub) {
            $activeSub->update(['ends_at' => \Carbon\Carbon::parse($activeSub->ends_at)->addDays($request->days)]);
        } else {
            // Check if on trial
            if ($user->trial_ends_at && $user->trial_ends_at > now()) {
                $user->update(['trial_ends_at' => \Carbon\Carbon::parse($user->trial_ends_at)->addDays($request->days)]);
            } else {
                // Was expired, give them a manual subscription extension
                $user->subscriptions()->create([
                    'plan' => 'manual',
                    'status' => 'active',
                    'amount' => 0,
                    'currency' => 'FCFA',
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($request->days),
                    'payment_method' => 'free',
                ]);
            }
        }

        return back()->with('success', 'Abonnement prolongé avec succès.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->paginate(20)->withQueryString();

        $manualPendingCount = Subscription::where('payment_method', 'manual')
                                ->where('status', 'pending')
                                ->count();

        $metrics = [
            'total_revenue' => Payment::where('status', 'success')->sum('amount'),
            'mrr' => Payment::where('status', 'success')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereHas('subscription', function($q) { $q->where('plan', 'monthly'); })->sum('amount'),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'manualPendingCount', 'metrics'));
    }

    public function validatePayment(Request $request, Subscription $subscription)
    {
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Cet abonnement n\'est pas en attente.');
        }

        $durationDays = $subscription->plan === 'yearly' ? 365 : 30;

        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
        ]);

        Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->amount,
            'currency' => $subscription->currency,
            'status' => 'success',
            'payment_method' => $subscription->payment_method,
            'transaction_id' => 'MANUAL-' . strtoupper(uniqid()),
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Paiement manuel validé et abonnement activé.');
    }
}

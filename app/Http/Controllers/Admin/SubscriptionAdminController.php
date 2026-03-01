<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class SubscriptionAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(20);
        $pendingPayments = Payment::with('user')->where('status', 'pending')->latest()->get();

        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $revenueThisMonth = Payment::where('status', 'success')->whereMonth('created_at', now()->month)->sum('amount');
        $mrr = Subscription::where('status', 'active')->where('ends_at', '>', now())->sum('amount');

        return view('admin.subscriptions.index', compact('subscriptions', 'pendingPayments', 'totalRevenue', 'revenueThisMonth', 'mrr'));
    }

    public function validate(Request $request, Subscription $subscription)
    {
        $subscription->update([
            'status' => 'active',
            'payment_reference' => $request->input('reference', 'ADMIN-' . now()->timestamp),
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ]);

        // Create payment record
        Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->amount,
            'currency' => $subscription->currency,
            'status' => 'success',
            'payment_method' => 'manual',
            'transaction_id' => $subscription->payment_reference,
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Abonnement validé avec succès.');
    }
}

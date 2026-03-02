<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest()
            ->first();

        $targetDate = null;
        $isTrial = false;

        if ($activeSubscription) {
            $targetDate = $activeSubscription->ends_at;
        } elseif ($user->trial_ends_at && $user->trial_ends_at->isFuture()) {
            $targetDate = $user->trial_ends_at;
            $isTrial = true;
        }

        $payments = $user->payments()->with('subscription')->latest()->get();

        return view('user.subscription.index', compact('activeSubscription', 'targetDate', 'isTrial', 'payments'));
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        
        $plan = $request->input('plan', 'monthly');
        $currency = config('fedapay.currency', 'XOF');

        if ($plan === 'yearly') {
            $amount = 10000;
            $durationDays = 365;
        } else {
            $amount = 1000;
            $durationDays = 30;
        }

        // Create pending subscription
        $subscription = $user->subscriptions()->create([
            'plan' => $plan,
            'status' => 'pending',
            'amount' => $amount,
            'currency' => $currency,
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'payment_method' => 'fedapay',
        ]);

        // Try to create FedaPay transaction
        try {
            \FedaPay\FedaPay::setApiKey(config('fedapay.secret_key'));
            \FedaPay\FedaPay::setEnvironment(config('fedapay.environment', 'sandbox'));

            $transaction = \FedaPay\Transaction::create([
                'description' => 'Abonnement FinanceZen - ' . $user->name,
                'amount' => $amount,
                'currency' => ['iso' => $currency],
                'callback_url' => route('subscription.callback') . '?subscription_id=' . $subscription->id,
                'customer' => [
                    'firstname' => $user->name,
                    'email' => $user->email,
                    'phone_number' => [
                        'number' => $user->phone ?? '+22900000000',
                        'country' => 'bj',
                    ],
                ],
            ]);

            $token = $transaction->generateToken();

            // Store FedaPay transaction ID
            $subscription->update(['payment_reference' => $transaction->id]);

            return redirect($token->url);
        } catch (\Exception $e) {
            // If FedaPay fails (keys not configured), fallback to manual
            $subscription->update(['payment_method' => 'manual']);

            return redirect()->route('subscription.index')
                ->with('warning', 'Paiement en ligne indisponible. Votre demande a été envoyée pour validation manuelle par l\'administrateur.');
        }
    }

    public function callback(Request $request)
    {
        $subscriptionId = $request->query('subscription_id');
        $status = $request->query('status');

        if ($subscriptionId && $status === 'approved') {
            $subscription = Subscription::find($subscriptionId);

            if ($subscription && $subscription->user_id === auth()->id()) {
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
                    'payment_method' => 'fedapay',
                    'transaction_id' => $subscription->payment_reference,
                    'payment_date' => now(),
                ]);

                return redirect()->route('subscription.index')
                    ->with('success', '🎉 Paiement confirmé ! Votre abonnement est maintenant actif.');
            }
        }

        return redirect()->route('subscription.index')
            ->with('error', 'Le paiement a échoué ou a été annulé. Veuillez réessayer.');
    }

    public function success()
    {
        return redirect()->route('subscription.index')
            ->with('success', 'Paiement confirmé ! Votre abonnement est actif.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Mail\PaymentConfirmed;
use App\Mail\PaymentFailed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    /**
     * Handle FedaPay webhook notifications.
     * The user will configure the webhook URL on FedaPay's dashboard.
     */
    public function fedapay(Request $request)
    {
        $payload = $request->all();

        Log::info('FedaPay Webhook received', $payload);

        // Verify webhook signature if secret is configured
        $webhookSecret = config('fedapay.webhook_secret');
        if ($webhookSecret) {
            $signature = $request->header('X-Fedapay-Signature');
            if (!$signature || !$this->verifySignature($request->getContent(), $signature, $webhookSecret)) {
                Log::warning('FedaPay Webhook: Invalid signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $event = $payload['event'] ?? null;
        $entity = $payload['entity'] ?? [];

        if (!$event) {
            return response()->json(['error' => 'No event'], 400);
        }

        match ($event) {
            'transaction.approved' => $this->handleTransactionApproved($entity),
            'transaction.declined', 'transaction.canceled' => $this->handleTransactionFailed($entity),
            default => Log::info("FedaPay Webhook: Unhandled event: {$event}"),
        };

        return response()->json(['status' => 'ok'], 200);
    }

    private function handleTransactionApproved(array $entity)
    {
        $transactionId = $entity['id'] ?? null;
        if (!$transactionId) return;

        $subscription = Subscription::where('payment_reference', $transactionId)->first();
        if (!$subscription || $subscription->status === 'active') return;

        $durationDays = $subscription->plan === 'yearly' ? 365 : 30;

        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
        ]);

        Payment::updateOrCreate(
            ['transaction_id' => (string) $transactionId],
            [
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'status' => 'success',
                'payment_method' => 'fedapay',
                'payment_date' => now(),
            ]
        );

        Log::info("FedaPay Webhook: Subscription #{$subscription->id} activated for user #{$subscription->user_id}");

        // Send payment confirmation email
        $payment = Payment::where('transaction_id', (string) $transactionId)->first();
        if ($payment) {
            Mail::to($subscription->user->email)->queue(new PaymentConfirmed($subscription->user, $payment));
        }
    }

    private function handleTransactionFailed(array $entity)
    {
        $transactionId = $entity['id'] ?? null;
        if (!$transactionId) return;

        $subscription = Subscription::where('payment_reference', $transactionId)->first();
        if (!$subscription) return;

        $subscription->update(['status' => 'failed']);

        Payment::updateOrCreate(
            ['transaction_id' => (string) $transactionId],
            [
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'status' => 'failed',
                'payment_method' => 'fedapay',
                'payment_date' => now(),
            ]
        );

        Log::warning("FedaPay Webhook: Transaction #{$transactionId} failed");

        // Send payment failed email
        Mail::to($subscription->user->email)->queue(new PaymentFailed($subscription->user));
    }

    private function verifySignature(string $payload, string $signature, string $secret): bool
    {
        $computed = hash_hmac('sha256', $payload, $secret);
        return hash_equals($computed, $signature);
    }
}

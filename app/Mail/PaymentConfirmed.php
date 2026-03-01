<?php
namespace App\Mail;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public User $user, public Payment $payment) {}
    public function envelope(): Envelope { return new Envelope(subject: '✅ Paiement confirmé — FinanceZen'); }
    public function content(): Content { return new Content(view: 'emails.payment-confirmed'); }
}

<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public User $user, public int $daysLeft = 3) {}
    public function envelope(): Envelope { return new Envelope(subject: "⏰ Votre essai expire dans {$this->daysLeft} jours"); }
    public function content(): Content { return new Content(view: 'emails.trial-expiry-reminder'); }
}

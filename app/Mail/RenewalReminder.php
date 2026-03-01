<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalReminder extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public User $user, public int $daysLeft = 5) {}
    public function envelope(): Envelope { return new Envelope(subject: "📅 Votre abonnement expire dans {$this->daysLeft} jours"); }
    public function content(): Content { return new Content(view: 'emails.renewal-reminder'); }
}

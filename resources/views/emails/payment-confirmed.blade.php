@extends('emails.layout')
@section('title', 'Paiement confirmé')
@section('content')
<h2>Merci {{ $user->name }} ! ✅</h2>
<p>Votre paiement a été confirmé avec succès.</p>
<div class="highlight">
    <strong>Récapitulatif</strong>
    <p style="margin-top:8px;">
        Montant : <strong>{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</strong><br>
        Date : <strong>{{ $payment->payment_date?->format('d/m/Y H:i') ?? now()->format('d/m/Y') }}</strong><br>
        Méthode : <strong>{{ ucfirst($payment->payment_method) }}</strong><br>
        Référence : <strong>{{ $payment->transaction_id ?? 'N/A' }}</strong>
    </p>
</div>
<p>Votre abonnement est actif pour les 30 prochains jours. Bonne gestion financière ! 🚀</p>
<a href="{{ url('/dashboard') }}" class="btn">Accéder à mon tableau de bord →</a>
@endsection

@extends('emails.layout')
@section('title', 'Renouvellement bientôt')
@section('content')
<h2>Bonjour {{ $user->name }},</h2>
<p>Votre abonnement FinanceZen expire dans <strong>{{ $daysLeft }} jour(s)</strong>.</p>
<div class="highlight">
    <strong>📅 Pensez au renouvellement</strong>
    <p style="margin-top:8px;">Pour éviter toute interruption de service, renouvelez votre abonnement dès maintenant.</p>
</div>
<a href="{{ url('/subscription') }}" class="btn">Renouveler mon abonnement →</a>
<p>Votre tarif reste inchangé : <strong>1 000 FCFA/mois</strong>.</p>
@endsection

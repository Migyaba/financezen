@extends('emails.layout')
@section('title', 'Échec de paiement')
@section('content')
<h2>{{ $user->name }}, votre paiement a échoué ❌</h2>
<p>Nous n'avons pas pu traiter votre dernier paiement pour l'abonnement FinanceZen.</p>
<div class="highlight">
    <strong>💳 Que faire ?</strong>
    <p style="margin-top:8px;">Vérifiez votre moyen de paiement et réessayez. Si le problème persiste, contactez notre support.</p>
</div>
<a href="{{ url('/subscription') }}" class="btn">Réessayer le paiement →</a>
<p style="font-size:13px; color:#94A3B8;">Si vous pensez qu'il s'agit d'une erreur, n'hésitez pas à nous contacter.</p>
@endsection

@extends('emails.layout')
@section('title', 'Votre essai expire bientôt')
@section('content')
<h2>Bonjour {{ $user->name }},</h2>
<p>Votre essai gratuit FinanceZen expire dans <strong>{{ $daysLeft }} jour(s)</strong>.</p>
<div class="highlight">
    <strong>⏰ Ne perdez pas vos données</strong>
    <p style="margin-top:8px;">Souscrivez dès maintenant à seulement <strong>1 000 FCFA/mois</strong> pour continuer à gérer vos finances en toute sérénité.</p>
</div>
<a href="{{ url('/subscription') }}" class="btn">Souscrire maintenant →</a>
<p>Vous avez des questions ? Répondez à cet email, nous sommes là pour vous aider.</p>
@endsection

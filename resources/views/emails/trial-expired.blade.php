@extends('emails.layout')
@section('title', 'Essai expiré')
@section('content')
<h2>{{ $user->name }}, votre essai a pris fin 🔒</h2>
<p>Votre période d'essai de 7 jours est maintenant terminée. L'accès aux fonctionnalités premium a été suspendu.</p>
<div class="highlight">
    <strong>💡 Bonne nouvelle</strong>
    <p style="margin-top:8px;">Vos données sont toujours en sécurité ! Souscrivez pour les retrouver immédiatement.</p>
</div>
<a href="{{ url('/subscription') }}" class="btn">Réactiver mon compte — 1 000 FCFA/mois →</a>
@endsection

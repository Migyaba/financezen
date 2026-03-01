@extends('emails.layout')
@section('title', 'Abonnement expiré')
@section('content')
<h2>{{ $user->name }}, votre abonnement a expiré 🔒</h2>
<p>Votre accès premium FinanceZen est maintenant suspendu.</p>
<div class="highlight">
    <strong>📊 Vos données sont en sécurité</strong>
    <p style="margin-top:8px;">Renouvelez votre abonnement pour retrouver immédiatement l'accès à toutes vos données et fonctionnalités.</p>
</div>
<a href="{{ url('/subscription') }}" class="btn btn-danger">Réactiver mon abonnement →</a>
@endsection

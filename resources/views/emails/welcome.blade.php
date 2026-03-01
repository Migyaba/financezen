@extends('emails.layout')
@section('title', 'Bienvenue !')
@section('content')
<h2>Bonjour {{ $user->name }} 👋</h2>
<p>Bienvenue sur <strong>FinanceZen</strong> ! Nous sommes ravis de vous compter parmi nous.</p>
<div class="highlight">
    <strong>🎁 7 jours d'essai gratuit</strong>
    <p style="margin-top:8px;">Profitez de toutes les fonctionnalités premium pendant 7 jours, sans engagement.</p>
</div>
<p>Voici ce que vous pouvez faire dès maintenant :</p>
<ul style="color:#64748B; padding-left:20px;">
    <li>📊 Créer votre premier budget mensuel</li>
    <li>💳 Enregistrer vos transactions</li>
    <li>🎯 Définir vos objectifs d'épargne</li>
    <li>📈 Suivre vos dettes et remboursements</li>
</ul>
<a href="{{ url('/dashboard') }}" class="btn">Commencer maintenant →</a>
<p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
@endsection

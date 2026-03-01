@extends('emails.layout')
@section('title', 'Dernier jour d\'essai')
@section('content')
<h2>{{ $user->name }}, c'est votre dernier jour ! ⚠️</h2>
<p>Demain, votre accès gratuit à FinanceZen sera suspendu.</p>
<div class="highlight">
    <strong>🔒 Dernière chance</strong>
    <p style="margin-top:8px;">Souscrivez maintenant pour garder l'accès à toutes vos données financières et continuez à progresser.</p>
</div>
<a href="{{ url('/subscription') }}" class="btn btn-danger">Souscrire avant demain →</a>
@endsection

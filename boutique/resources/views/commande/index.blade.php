@extends('layouts.app')
@section('titre', 'Mes commandes')
@section('contenu')
<h1>Mes commandes</h1>
@forelse($commandes as $c)
  <div class="carte">
    <a href="{{ route('commandes.show', $c->id) }}"><strong>{{ $c->numero }}</strong></a> — {{ $c->libelle }}<br>
    {{ number_format($c->montant_total, 0, ',', ' ') }} FCFA · {{ $c->date_commande }}
  </div>
@empty
  <div class="vide">Tu n'as pas encore de commande.</div>
@endforelse
@endsection

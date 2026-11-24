@extends('layouts.app')
@section('titre', 'Commandes (administrateur)')
@section('contenu')
<h1>Commandes</h1>
<form class="inline" method="GET" action="{{ route('admin.commandes') }}">
  <select name="statut">
    <option value="">Tous les statuts</option>
    @foreach(\App\Models\Commande::LIBELLES as $cle => $lib)<option value="{{ $cle }}" @selected($statut === $cle)>{{ $lib }}</option>@endforeach
  </select>
  <button type="submit">Filtrer</button>
</form>

@forelse($commandes as $c)
  <div class="carte">
    <strong>{{ $c->numero }}</strong> — {{ $c->libelle }} — {{ number_format($c->montant_total, 0, ',', ' ') }} FCFA<br>
    Client : {{ $c->client->nom ?? '' }} ({{ $c->client->email ?? '' }})<br>
    @if($c->adresse)Livraison : {{ $c->adresse->nom_destinataire }}, {{ $c->adresse->telephone }}, {{ $c->adresse->quartier }} {{ $c->adresse->ville }}<br>@endif
    <ul>@foreach($c->lignes as $l)<li>{{ $l->quantite }} × {{ $l->nom_produit }} ({{ $l->taille }} · {{ $l->couleur }})</li>@endforeach</ul>
    @if(isset($suivant[$c->statut]))
      <form class="inline" method="POST" action="{{ route('admin.commande.statut', $c->id) }}">
        @csrf
        <input type="hidden" name="statut" value="{{ $suivant[$c->statut] }}">
        <button type="submit">Passer à « {{ \App\Models\Commande::LIBELLES[$suivant[$c->statut]] }} »</button>
      </form>
    @endif
  </div>
@empty
  <div class="vide">Aucune commande.</div>
@endforelse
@endsection

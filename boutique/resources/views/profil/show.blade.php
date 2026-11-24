@extends('layouts.app')
@section('titre', 'Mon profil')
@section('contenu')
<h1>Mon profil</h1>
<form class="carte" method="POST" action="{{ route('profil.update') }}">
  @csrf
  <label for="nom">Nom</label><input id="nom" name="nom" value="{{ old('nom', $user->nom) }}">
  <label for="prenom">Prénom</label><input id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}">
  <label for="telephone">Téléphone</label><input id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}">
  <p>E-mail : {{ $user->email }}</p>
  <p><button type="submit">Enregistrer</button></p>
</form>

<h2>Mes adresses de livraison</h2>
@forelse($adresses as $a)
  <div class="carte">
    {{ $a->nom_destinataire }} · {{ $a->telephone }}<br>
    {{ $a->quartier }}{{ $a->quartier ? ', ' : '' }}{{ $a->ville }}
    @if($a->est_principale) <strong>(adresse principale)</strong>@endif
  </div>
@empty
  <div class="vide">Aucune adresse pour le moment.</div>
@endforelse

<h3>Ajouter une adresse</h3>
<form class="carte" method="POST" action="{{ route('adresse.store') }}">
  @csrf
  <label for="nd">Nom du destinataire</label><input id="nd" name="nom_destinataire" value="{{ old('nom_destinataire') }}">
  <label for="tel">Téléphone</label><input id="tel" name="telephone" value="{{ old('telephone') }}">
  <label for="quartier">Quartier</label><input id="quartier" name="quartier" value="{{ old('quartier') }}">
  <label for="ville">Ville</label><input id="ville" name="ville" value="{{ old('ville', 'Ambam') }}">
  <label><input type="checkbox" name="est_principale" value="1"> Définir comme adresse principale</label>
  <p><button type="submit">Ajouter l'adresse</button></p>
</form>
@endsection

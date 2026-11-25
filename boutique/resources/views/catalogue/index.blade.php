@extends('layouts.app')
@section('titre', 'Nos produits')
@section('contenu')
<h1>Nos produits</h1>
<form class="filtres" autocomplete="off" method="GET" action="{{ url('/produits') }}">
  <div class="grille">
    <div><label for="q">Recherche</label><input type="text" id="q" name="q" value="{{ $q }}" placeholder="Ex : maillot, ballon..."></div>
    <div><label for="sport">Sport</label>
      <select id="sport" name="sport"><option value="">Tous les sports</option>
        @foreach($sports as $s)<option value="{{ $s }}" @selected($s == $sport)>{{ $s }}</option>@endforeach
      </select></div>
    <div><label for="taille">Taille</label>
      <select id="taille" name="taille"><option value="">Toutes les tailles</option>
        @foreach($tailles as $t)<option value="{{ $t }}" @selected($t == $taille)>{{ $t }}</option>@endforeach
      </select></div>
    <div><label for="prix_min">Prix min (FCFA)</label><input type="number" min="0" id="prix_min" name="prix_min" value="{{ $prixMin }}"></div>
    <div><label for="prix_max">Prix max (FCFA)</label><input type="number" min="0" id="prix_max" name="prix_max" value="{{ $prixMax }}"></div>
  </div>
  <p><button type="submit">Filtrer</button> <a href="{{ url('/produits') }}">Réinitialiser</a></p>
</form>

<p>{{ $total }} produit{{ $total > 1 ? 's' : '' }} trouvé{{ $total > 1 ? 's' : '' }}</p>

@forelse($groupes as $cat => $produits)
  <h2>{{ $cat }}</h2>
  <ul class="cartes">
    @foreach($produits as $p)
      <li><a href="{{ url('/produit/'.$p->id) }}">
        <div>{{ $p->nom }}</div>
        <div class="sport">{{ $p->sport }}</div>
        <div class="prix">{{ number_format($p->prix, 0, ',', ' ') }} FCFA</div>
      </a></li>
    @endforeach
  </ul>
@empty
  <div class="vide">Aucun produit trouvé. Essaie de changer ou de retirer un filtre.</div>
@endforelse
@endsection

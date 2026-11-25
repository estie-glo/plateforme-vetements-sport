@extends('layouts.app')
@section('titre', $produit->nom)
@section('contenu')
@php $panierPret = Route::has('panier.ajouter'); @endphp
<p><a href="{{ url('/produits') }}">← Retour aux produits</a></p>
<h1>{{ $produit->nom }}</h1>
<p class="sport">{{ $produit->categorie->nom }} · {{ $produit->sport }}</p>
@if($produit->description)<p>{{ $produit->description }}</p>@endif

<form class="fiche" method="POST" action="{{ $panierPret ? route('panier.ajouter') : '#' }}">
  @csrf
  <label for="variante">Taille et couleur</label>
  <select id="variante" name="variante_id">
    <option value="" data-prix="{{ $produit->prix }}" data-stock="">— Choisir une taille et une couleur —</option>
    @foreach($produit->variantes as $v)
      <option value="{{ $v->id }}" data-prix="{{ $v->prix_specifique ?? $produit->prix }}" data-stock="{{ $v->stock }}" @disabled($v->stock <= 0)>
        {{ $v->taille ?? 'Unique' }} · {{ $v->couleur ?? 'Unique' }}{{ $v->stock <= 0 ? ' (indisponible)' : '' }}
      </option>
    @endforeach
  </select>
  <div class="prix-grand" id="prix">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
  <div id="dispo"></div>
  <label for="quantite">Quantité</label>
  <input type="number" id="quantite" name="quantite" min="1" value="1">
  <p><button type="submit" @disabled(!$panierPret)>Ajouter au panier</button></p>
</form>

<script>
const sel = document.getElementById('variante');
sel.addEventListener('change', () => {
  const o = sel.options[sel.selectedIndex];
  document.getElementById('prix').textContent = Number(o.dataset.prix).toLocaleString('fr-FR') + ' FCFA';
  const stock = o.dataset.stock;
  document.getElementById('dispo').innerHTML = stock === '' ? '' : '<span class="dispo">En stock (' + stock + ' disponibles)</span>';
});
</script>
@endsection

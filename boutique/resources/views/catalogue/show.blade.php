@extends('layouts.app')
@section('titre', $produit->nom)
@section('contenu')
@php
  $panierPret = Route::has('panier.ajouter');
  $emoji = ['Ballons' => '⚽', 'Chaussures' => '👟', 'Maillots' => '👕', 'Survêtements' => '🧥'][$produit->categorie->nom] ?? '🏅';
@endphp
<p><a href="{{ url('/produits') }}">← Retour aux produits</a></p>
<div style="display:grid;gap:28px;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));align-items:start">
  <div class="card" style="margin:0;height:340px;display:flex;align-items:center;justify-content:center;font-size:120px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0)">{{ $emoji }}</div>
  <div>
    <span class="badge">{{ $produit->categorie->nom }} · {{ $produit->sport }}</span>
    <h1 style="margin-top:10px">{{ $produit->nom }}</h1>
    @if($produit->description)<p class="muted">{{ $produit->description }}</p>@endif

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
      <p style="margin:18px 0 0"><button type="submit" @disabled(!$panierPret)>Ajouter au panier</button></p>
    </form>
  </div>
</div>

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

@extends('layouts.app')
@section('titre', 'Gestion des produits')
@section('contenu')
<h1>Gestion des produits</h1>

<form class="carte" method="POST" action="{{ route('admin.produit.store') }}">
  @csrf
  <h3>Nouveau produit (créé en brouillon)</h3>
  <label>Nom</label><input name="nom" value="{{ old('nom') }}">
  <label>Description</label><textarea name="description">{{ old('description') }}</textarea>
  <label>Prix (FCFA)</label><input type="number" min="0" name="prix" value="{{ old('prix') }}">
  <label>Sport</label><input name="sport" value="{{ old('sport') }}">
  <label>Catégorie</label>
  <select name="categorie_id">@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->nom }}</option>@endforeach</select>
  <p><button type="submit">Créer le produit</button></p>
</form>

@foreach($produits as $p)
  <div class="carte">
    <strong>{{ $p->nom }}</strong> — {{ number_format($p->prix, 0, ',', ' ') }} FCFA — statut : <strong>{{ $p->statut }}</strong>
    <form class="inline" method="POST" action="{{ route('admin.produit.statut', $p->id) }}">
      @csrf
      <select name="statut">@foreach(['brouillon', 'disponible', 'retire'] as $s)<option value="{{ $s }}" @selected($p->statut === $s)>{{ $s }}</option>@endforeach</select>
      <button type="submit">Changer</button>
    </form>
    <table>
      <tr><th>Taille</th><th>Couleur</th><th>SKU</th><th>Stock</th></tr>
      @foreach($p->variantes as $v)
        <tr>
          <td>{{ $v->taille }}</td><td>{{ $v->couleur }}</td><td>{{ $v->reference_sku }}</td>
          <td><form class="inline" method="POST" action="{{ route('admin.variante.stock', $v->id) }}">@csrf
            <input type="number" min="0" name="stock" value="{{ $v->stock }}" style="width:80px"><button type="submit">OK</button></form></td>
        </tr>
      @endforeach
    </table>
    <form class="inline" method="POST" action="{{ route('admin.variante.store', $p->id) }}" style="flex-wrap:wrap;margin-top:8px">
      @csrf
      <input name="taille" placeholder="Taille" style="width:80px">
      <input name="couleur" placeholder="Couleur" style="width:100px">
      <input name="reference_sku" placeholder="SKU" style="width:110px">
      <input type="number" min="0" name="stock" placeholder="Stock" style="width:80px">
      <input type="number" min="0" name="prix_specifique" placeholder="Prix spécifique" style="width:120px">
      <button type="submit">Ajouter la variante</button>
    </form>
  </div>
@endforeach
@endsection

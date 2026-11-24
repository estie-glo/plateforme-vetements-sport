@extends('layouts.app')
@section('titre', 'Mon panier')
@section('contenu')
<h1>Mon panier</h1>
@if($lignes->isEmpty())
  <div class="vide">Ton panier est vide. <a href="{{ url('/produits') }}">Voir les produits</a></div>
@else
  <table>
    <tr><th>Article</th><th>Prix</th><th>Quantité</th><th>Sous-total</th><th></th></tr>
    @foreach($lignes as $l)
      <tr>
        <td>{{ $l->variante->produit->nom }}<br><span class="sport">{{ $l->variante->taille }} · {{ $l->variante->couleur }}</span></td>
        <td>{{ number_format($l->variante->prix_final, 0, ',', ' ') }}</td>
        <td>
          <form class="inline" method="POST" action="{{ route('panier.modifier', $l->id) }}">
            @csrf
            <input type="number" name="quantite" min="1" value="{{ $l->quantite }}" style="width:70px">
            <button type="submit">OK</button>
          </form>
        </td>
        <td>{{ number_format($l->variante->prix_final * $l->quantite, 0, ',', ' ') }}</td>
        <td><form class="inline" method="POST" action="{{ route('panier.supprimer', $l->id) }}">@csrf<button class="lien" type="submit">Retirer</button></form></td>
      </tr>
    @endforeach
  </table>
  <p class="prix-grand">Total : {{ number_format($total, 0, ',', ' ') }} FCFA</p>
  <a class="btn" href="{{ route('commande.recap') }}">Passer la commande</a>
@endif
@endsection

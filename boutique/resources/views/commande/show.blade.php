@extends('layouts.app')
@section('titre', 'Commande '.$commande->numero)
@section('contenu')
<p><a href="{{ route('commandes.index') }}">← Mes commandes</a></p>
<h1>Commande {{ $commande->numero }}</h1>
<p>Statut : <strong>{{ $commande->libelle }}</strong></p>
<table>
  <tr><th>Article</th><th>Prix</th><th>Qté</th><th>Sous-total</th></tr>
  @foreach($commande->lignes as $l)
    <tr><td>{{ $l->nom_produit }} ({{ $l->taille }} · {{ $l->couleur }})</td><td>{{ number_format($l->prix_unitaire, 0, ',', ' ') }}</td><td>{{ $l->quantite }}</td><td>{{ number_format($l->sous_total, 0, ',', ' ') }}</td></tr>
  @endforeach
</table>
<p>Frais de livraison : {{ number_format($commande->frais_livraison, 0, ',', ' ') }} FCFA</p>
<p class="prix-grand">Total : {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</p>
@if($commande->adresse)<p>Livraison : {{ $commande->adresse->nom_destinataire }}, {{ $commande->adresse->quartier }} {{ $commande->adresse->ville }}</p>@endif
@if($commande->statut === 'en_attente' && Route::has('paiement.form'))
  <a class="btn" href="{{ route('paiement.form', $commande->id) }}">Payer cette commande</a>
@endif
@endsection

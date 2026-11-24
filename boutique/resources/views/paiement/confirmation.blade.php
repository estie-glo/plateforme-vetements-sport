@extends('layouts.app')
@section('titre', 'Paiement confirmé')
@section('contenu')
<h1>Paiement confirmé</h1>
<p class="ok">Merci ! Ta commande <strong>{{ $commande->numero }}</strong> est payée.</p>
<p>Montant : {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</p>
<p class="test">Paiement simulé (mode test).</p>
<a class="btn" href="{{ route('commandes.show', $commande->id) }}">Voir ma commande</a>
@endsection

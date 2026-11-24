@extends('layouts.app')
@section('titre', 'Récapitulatif de commande')
@section('contenu')
<h1>Récapitulatif de la commande</h1>
<table>
  <tr><th>Article</th><th>Prix</th><th>Qté</th><th>Sous-total</th></tr>
  @foreach($lignes as $l)
    <tr>
      <td>{{ $l->variante->produit->nom }} ({{ $l->variante->taille }} · {{ $l->variante->couleur }})</td>
      <td>{{ number_format($l->variante->prix_final, 0, ',', ' ') }}</td>
      <td>{{ $l->quantite }}</td>
      <td>{{ number_format($l->variante->prix_final * $l->quantite, 0, ',', ' ') }}</td>
    </tr>
  @endforeach
</table>

<form class="carte" method="GET" action="{{ route('commande.recap') }}">
  <label for="adresse_id">Adresse de livraison</label>
  <select id="adresse_id" name="adresse_id" onchange="this.form.submit()">
    @foreach($adresses as $a)
      <option value="{{ $a->id }}" @selected($a->id == $adresse->id)>{{ $a->nom_destinataire }} — {{ $a->quartier }} {{ $a->ville }}</option>
    @endforeach
  </select>
</form>

<p>Sous-total : {{ number_format($sousTotal, 0, ',', ' ') }} FCFA<br>
Frais de livraison ({{ $adresse->ville }}) : {{ number_format($frais, 0, ',', ' ') }} FCFA</p>
<p class="prix-grand">Total à payer : {{ number_format($total, 0, ',', ' ') }} FCFA</p>

<form method="POST" action="{{ route('commande.valider') }}">
  @csrf
  <input type="hidden" name="adresse_id" value="{{ $adresse->id }}">
  <button type="submit">Valider la commande</button>
</form>
@endsection

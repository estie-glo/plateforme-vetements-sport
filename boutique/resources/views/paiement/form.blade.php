@extends('layouts.app')
@section('titre', 'Paiement')
@section('contenu')
<h1>Paiement de la commande {{ $commande->numero }}</h1>
<p class="test">MODE TEST : aucun argent réel n'est débité. Un numéro qui se termine par 00 simule un paiement refusé.</p>
<p class="prix-grand">Montant à payer : {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</p>
<form class="carte" method="POST" action="{{ route('paiement.payer', $commande->id) }}">
  @csrf
  <label for="methode">Moyen de paiement</label>
  <select id="methode" name="methode">
    <option value="">— Choisir —</option>
    <option value="orange_money" @selected(old('methode') === 'orange_money')>Orange Money</option>
    <option value="mtn_momo" @selected(old('methode') === 'mtn_momo')>MTN MoMo</option>
  </select>
  <label for="telephone">Numéro de téléphone (ex : 677123456)</label>
  <input id="telephone" name="telephone" value="{{ old('telephone') }}" inputmode="numeric">
  <p><button type="submit">Payer {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</button></p>
</form>
@endsection

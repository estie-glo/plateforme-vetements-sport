@extends('layouts.app')
@section('titre', 'Paiement')
@section('contenu')
<div style="max-width:480px;margin:20px auto">
  <h1>Paiement</h1>
  <p class="muted">Commande {{ $commande->numero }}</p>
  <p class="test">MODE TEST : aucun argent réel n'est débité. Un numéro qui se termine par 00 simule un paiement refusé.</p>
  <form class="card" method="POST" action="{{ route('paiement.payer', $commande->id) }}">
    @csrf
    <div class="muted">Montant à payer</div>
    <div class="prix-grand" style="margin:4px 0 8px">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</div>
    <label for="methode">Moyen de paiement</label>
    <select id="methode" name="methode">
      <option value="">— Choisir —</option>
      <option value="orange_money" @selected(old('methode') === 'orange_money')>🟠 Orange Money</option>
      <option value="mtn_momo" @selected(old('methode') === 'mtn_momo')>🟡 MTN MoMo</option>
    </select>
    <label for="telephone">Numéro de téléphone (ex : 677123456)</label>
    <input id="telephone" name="telephone" value="{{ old('telephone') }}" inputmode="numeric">
    <p style="margin:20px 0 0"><button type="submit" style="width:100%">Payer {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</button></p>
  </form>
</div>
@endsection

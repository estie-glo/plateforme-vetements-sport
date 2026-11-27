#!/usr/bin/env bash
# Design D : pages connexion, inscription (lot L1) et paiement (lot L4)
# Branche : feature/design-paiement   (à lancer depuis feature/design-layout)
set -e
cd ~/plateforme-vetements-sport
git config user.name >/dev/null || { echo ">> Configure d'abord ton identité : git config --global user.name \"Ton Nom\""; exit 1; }
BR=$(git branch --show-current)
[ "$BR" = "feature/design-layout" ] || [ "$BR" = "feature/design-paiement" ] || { echo ">> Place-toi d'abord sur feature/design-layout : git fetch && git switch feature/design-layout (branche actuelle : $BR)"; exit 1; }
git switch -q -C feature/design-paiement
cd boutique

cat > resources/views/auth/connexion.blade.php <<'EOF_BLADE'
@extends('layouts.app')
@section('titre', 'Connexion')
@section('contenu')
<div style="max-width:440px;margin:30px auto">
  <h1 style="text-align:center">Content de te revoir 👋</h1>
  <form class="card" method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}">
    <label for="password">Mot de passe</label><input id="password" type="password" name="password">
    <p style="margin:20px 0 0"><button type="submit" style="width:100%">Se connecter</button></p>
  </form>
  <p class="muted" style="text-align:center">Pas de compte ? <a href="{{ route('inscription') }}">S'inscrire</a></p>
</div>
@endsection
EOF_BLADE

cat > resources/views/auth/inscription.blade.php <<'EOF_BLADE'
@extends('layouts.app')
@section('titre', 'Inscription')
@section('contenu')
<div style="max-width:440px;margin:30px auto">
  <h1 style="text-align:center">Crée ton compte</h1>
  <form class="card" method="POST" action="{{ route('inscription') }}">
    @csrf
    <label for="nom">Nom</label><input id="nom" name="nom" value="{{ old('nom') }}">
    <label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}">
    <label for="telephone">Téléphone (facultatif)</label><input id="telephone" name="telephone" value="{{ old('telephone') }}">
    <label for="password">Mot de passe (8 caractères minimum)</label><input id="password" type="password" name="password">
    <p style="margin:20px 0 0"><button type="submit" style="width:100%">Créer mon compte</button></p>
  </form>
  <p class="muted" style="text-align:center">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
</div>
@endsection
EOF_BLADE

mkdir -p resources/views/paiement
cat > resources/views/paiement/form.blade.php <<'EOF_BLADE'
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
EOF_BLADE

cd ..
git add -A
git commit -q -m "Design : pages connexion, inscription et paiement Mobile Money" || true
git push -u --force-with-lease origin feature/design-paiement || echo ">> Push a faire toi-meme : git push -u origin feature/design-paiement"
echo "Branche feature/design-paiement poussee. Ouvre une Pull Request vers main."

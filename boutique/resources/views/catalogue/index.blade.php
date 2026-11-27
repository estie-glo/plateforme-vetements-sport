@extends('layouts.app')
@section('titre', 'Nos produits')
@section('contenu')
@php
  $emoji = ['Ballons' => '⚽', 'Chaussures' => '👟', 'Maillots' => '👕', 'Survêtements' => '🧥'];
  $teinte = ['Ballons' => '#ffedd5,#fed7aa', 'Chaussures' => '#dbeafe,#bfdbfe', 'Maillots' => '#dcfce7,#bbf7d0', 'Survêtements' => '#ede9fe,#ddd6fe'];
@endphp
<section style="background:linear-gradient(135deg,#0b1220,#1e293b);color:#fff;border-radius:20px;padding:40px 32px;margin-bottom:24px">
  <span class="badge">Nouvelle collection</span>
  <h1 style="color:#fff;margin-top:12px">L'équipement des champions,<br>livré à Ambam.</h1>
  <p style="color:#cbd5e1;max-width:520px">Ballons, chaussures, maillots et survêtements. Paiement Orange Money et MTN MoMo.</p>
</section>

<form class="filtres" method="GET" action="{{ url('/produits') }}" autocomplete="off">
  <div class="grille">
    <div><label for="q">Recherche</label><input type="text" id="q" name="q" value="{{ $q }}" placeholder="Ex : maillot, ballon..."></div>
    <div><label for="sport">Sport</label>
      <select id="sport" name="sport"><option value="">Tous les sports</option>
        @foreach($sports as $s)<option value="{{ $s }}" @selected($s == $sport)>{{ $s }}</option>@endforeach
      </select></div>
    <div><label for="taille">Taille</label>
      <select id="taille" name="taille"><option value="">Toutes les tailles</option>
        @foreach($tailles as $t)<option value="{{ $t }}" @selected($t == $taille)>{{ $t }}</option>@endforeach
      </select></div>
    <div><label for="prix_min">Prix min (FCFA)</label><input type="number" min="0" id="prix_min" name="prix_min" value="{{ $prixMin }}"></div>
    <div><label for="prix_max">Prix max (FCFA)</label><input type="number" min="0" id="prix_max" name="prix_max" value="{{ $prixMax }}"></div>
  </div>
  <p style="margin:16px 0 0"><button type="submit">Filtrer</button> &nbsp; <a href="{{ url('/produits') }}">Réinitialiser</a></p>
</form>

<p class="muted">{{ $total }} produit{{ $total > 1 ? 's' : '' }} trouvé{{ $total > 1 ? 's' : '' }}</p>

@forelse($groupes as $cat => $produits)
  <h2>{{ $emoji[$cat] ?? '🏅' }} {{ $cat }}</h2>
  <div style="display:grid;gap:18px;grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
    @foreach($produits as $p)
      <a href="{{ url('/produit/'.$p->id) }}" class="card" style="margin:0;padding:0;overflow:hidden;color:inherit;text-decoration:none">
        <div style="height:150px;display:flex;align-items:center;justify-content:center;font-size:58px;background:linear-gradient(135deg,{{ $teinte[$cat] ?? '#e2e8f0,#cbd5e1' }})">{{ $emoji[$cat] ?? '🏅' }}</div>
        <div style="padding:16px">
          <span class="badge">{{ $p->sport }}</span>
          <div style="font-weight:700;margin:8px 0 4px">{{ $p->nom }}</div>
          <div class="prix">{{ number_format($p->prix, 0, ',', ' ') }} FCFA</div>
        </div>
      </a>
    @endforeach
  </div>
@empty
  <div class="vide">Aucun produit trouvé. Essaie de changer ou de retirer un filtre.</div>
@endforelse
@endsection

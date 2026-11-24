<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('titre', 'Boutique sport Ambam')</title>
<style>
body{font-family:sans-serif;max-width:960px;margin:0 auto;padding:16px;color:#111}
a{color:#1d4ed8}
nav{display:flex;gap:14px;flex-wrap:wrap;align-items:center;border-bottom:1px solid #ddd;padding-bottom:10px;margin-bottom:16px}
nav form{margin:0}
ul.cartes{list-style:none;padding:0;display:grid;gap:12px;grid-template-columns:repeat(auto-fill,minmax(250px,1fr))}
ul.cartes li{border:1px solid #ccc;border-radius:6px;padding:12px}
ul.cartes a{text-decoration:none;color:inherit;display:block}
.sport{color:#666;font-size:14px}.prix{font-weight:bold}.prix-grand{font-size:28px;font-weight:bold;margin:12px 0}
.filtres,.carte,.fiche{border:1px solid #ccc;border-radius:8px;padding:16px;margin:16px 0;background:#fafafa}
.grille{display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(160px,1fr))}
label{display:block;font-size:14px;font-weight:bold;margin:10px 0 4px}
input,select,textarea{width:100%;padding:8px;font-size:15px;box-sizing:border-box}
input[type=checkbox]{width:auto}
button,.btn{padding:10px 18px;font-size:15px;border:0;border-radius:6px;background:#1d4ed8;color:#fff;cursor:pointer;text-decoration:none;display:inline-block}
button:disabled{background:#9ca3af;cursor:not-allowed}
.lien{background:none;color:#1d4ed8;padding:0;font-size:16px;text-decoration:underline}
.ok{color:#15803d;font-weight:bold}
.erreur{color:#b91c1c;background:#fef2f2;padding:10px;border-radius:6px;margin:10px 0}
.vide{padding:24px;text-align:center;color:#666;border:1px dashed #ccc;border-radius:8px}
table{width:100%;border-collapse:collapse}td,th{border-bottom:1px solid #ddd;padding:6px;text-align:left}
.inline{display:inline-flex;gap:6px;align-items:center;margin:0}.inline input,.inline select{width:auto}
.test{background:#fef3c7;padding:8px;border-radius:6px}
.indispo{color:#b45309}.dispo{color:#15803d}
</style>
</head>
<body>
<nav>
  <a href="{{ url('/produits') }}"><strong>Boutique sport Ambam</strong></a>
  @auth
    @if(Route::has('panier.index'))<a href="{{ route('panier.index') }}">Panier</a>@endif
    @if(Route::has('commandes.index'))<a href="{{ route('commandes.index') }}">Mes commandes</a>@endif
    <a href="{{ route('profil') }}">Profil</a>
    @if(Auth::user()->estAdmin() && Route::has('admin.produits'))
      <a href="{{ route('admin.produits') }}">Produits (admin)</a>
      <a href="{{ route('admin.commandes') }}">Commandes (admin)</a>
    @endif
    <form method="POST" action="{{ route('deconnexion') }}">@csrf<button class="lien" type="submit">Déconnexion</button></form>
  @else
    <a href="{{ route('login') }}">Connexion</a>
    <a href="{{ route('inscription') }}">Inscription</a>
  @endauth
</nav>
@if(session('ok'))<p class="ok">{{ session('ok') }}</p>@endif
@if($errors->any())<div class="erreur">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
@yield('contenu')
</body>
</html>

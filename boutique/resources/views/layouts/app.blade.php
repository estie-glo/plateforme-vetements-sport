<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('titre', 'Ambam Sport')</title>
<style>
:root{--bg:#f5f6fa;--card:#fff;--ink:#0f172a;--muted:#64748b;--line:#e2e8f0;--brand:#ff5a1f;--brand-d:#e0460f;--navy:#0b1220;--ok:#16a34a;--err:#dc2626;--r:16px;--shadow:0 8px 28px rgba(15,23,42,.08)}
*{box-sizing:border-box}
body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;background:var(--bg);color:var(--ink);line-height:1.55}
a{color:var(--brand);text-decoration:none}a:hover{text-decoration:underline}
header.top{position:sticky;top:0;z-index:20;background:var(--navy)}
.nav{max-width:1080px;margin:0 auto;padding:14px 20px;display:flex;gap:20px;align-items:center;flex-wrap:wrap}
.nav a,.nav .lien{color:#cbd5e1;font-weight:500;font-size:15px}.nav a:hover{color:#fff;text-decoration:none}
.nav form{margin:0}
.brand{font-weight:800;font-size:21px;color:#fff!important;letter-spacing:-.4px;margin-right:auto}.brand span{color:var(--brand)}
main{max-width:1080px;margin:0 auto;padding:28px 20px 70px}
h1{font-size:34px;letter-spacing:-.8px;margin:.2em 0 .5em;line-height:1.15}
h2{font-size:22px;letter-spacing:-.3px;margin:1.8em 0 .7em}
h3{margin:.2em 0 .6em}
.sport,.muted{color:var(--muted);font-size:14px}
.prix{font-weight:800;font-size:18px}.prix-grand{font-size:30px;font-weight:800;letter-spacing:-.5px;margin:14px 0}
.btn,button{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 20px;font-size:15px;font-weight:600;border:0;border-radius:12px;background:var(--brand);color:#fff;cursor:pointer;transition:.15s;text-decoration:none}
.btn:hover,button:hover{background:var(--brand-d);text-decoration:none;transform:translateY(-1px)}
button:disabled{background:#cbd5e1;cursor:not-allowed;transform:none}
.lien{background:none;padding:0;border-radius:0;color:var(--brand);font-weight:500}.lien:hover{background:none;transform:none;text-decoration:underline}
.card,.carte,.fiche,.filtres{background:var(--card);border:1px solid var(--line);border-radius:var(--r);padding:20px;margin:18px 0;box-shadow:var(--shadow)}
.grille{display:grid;gap:14px;grid-template-columns:repeat(auto-fit,minmax(170px,1fr))}
label{display:block;font-size:13px;font-weight:600;color:var(--muted);margin:12px 0 6px}
input,select,textarea{width:100%;padding:11px 13px;font-size:15px;border:1px solid var(--line);border-radius:12px;background:#fff;color:var(--ink);font-family:inherit}
input:focus,select:focus,textarea:focus{outline:2px solid rgba(255,90,31,.35);border-color:var(--brand)}
input[type=checkbox]{width:auto}
.ok{color:#166534;background:#dcfce7;padding:12px 16px;border-radius:12px;font-weight:600}
.erreur{color:#991b1b;background:#fee2e2;padding:12px 16px;border-radius:12px;margin:12px 0}
.vide{padding:36px;text-align:center;color:var(--muted);background:#fff;border:2px dashed var(--line);border-radius:var(--r)}
.test{background:#fef3c7;color:#92400e;padding:10px 14px;border-radius:12px;font-size:14px}
.dispo{color:var(--ok);font-weight:600}.indispo{color:#b45309}
.badge{display:inline-block;padding:3px 10px;border-radius:999px;background:#fff1ec;color:var(--brand-d);font-size:12px;font-weight:700}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:var(--r);overflow:hidden;box-shadow:var(--shadow)}
th{background:#f1f5f9;color:var(--muted);font-size:13px;text-transform:uppercase;letter-spacing:.4px}
td,th{padding:12px 14px;text-align:left;border-bottom:1px solid var(--line)}
.inline{display:inline-flex;gap:8px;align-items:center;margin:0}.inline input,.inline select{width:auto}
footer{text-align:center;color:var(--muted);font-size:13px;padding:30px 0}
@media(max-width:640px){h1{font-size:27px}.nav{gap:12px}}
</style>
</head>
<body>
<header class="top"><nav class="nav">
  <a class="brand" href="{{ url('/produits') }}">Ambam<span>Sport</span></a>
  <a href="{{ url('/produits') }}">Boutique</a>
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
    <a class="btn" href="{{ route('inscription') }}">Inscription</a>
  @endauth
</nav></header>
<main>
@if(session('ok'))<p class="ok">{{ session('ok') }}</p>@endif
@if($errors->any())<div class="erreur">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
@yield('contenu')
</main>
<footer>© 2026 AmbamSport · Équipement sportif à Ambam</footer>
</body>
</html>

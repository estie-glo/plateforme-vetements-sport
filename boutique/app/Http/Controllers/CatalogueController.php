<?php
namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Variante;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $r)
    {
        $q = trim((string) $r->query('q', ''));
        $sport = trim((string) $r->query('sport', ''));
        $taille = trim((string) $r->query('taille', ''));
        $prixMin = is_numeric($r->query('prix_min')) && $r->query('prix_min') >= 0 ? (int) $r->query('prix_min') : null;
        $prixMax = is_numeric($r->query('prix_max')) && $r->query('prix_max') >= 0 ? (int) $r->query('prix_max') : null;

        $req = Produit::with('categorie')->where('statut', 'disponible');
        if ($q !== '') {
            $req->where(function ($w) use ($q) {
                $w->where('nom', 'like', "%$q%")->orWhere('description', 'like', "%$q%");
            });
        }
        if ($sport !== '') $req->where('sport', $sport);
        if ($taille !== '') {
            $req->whereHas('variantes', fn ($v) => $v->where('taille', $taille)->where('stock', '>', 0));
        }
        if ($prixMin !== null) $req->where('prix', '>=', $prixMin);
        if ($prixMax !== null) $req->where('prix', '<=', $prixMax);

        $produits = $req->orderBy('nom')->get();
        $total = $produits->count();
        $groupes = $produits->groupBy(fn ($p) => $p->categorie->nom)->sortKeys();
        $sports = Produit::where('statut', 'disponible')->whereNotNull('sport')->distinct()->orderBy('sport')->pluck('sport');
        $tailles = Variante::whereNotNull('taille')->distinct()->orderBy('taille')->pluck('taille');

        return view('catalogue.index', compact('q', 'sport', 'taille', 'prixMin', 'prixMax', 'total', 'groupes', 'sports', 'tailles'));
    }

    public function show($id)
    {
        $produit = Produit::with(['categorie', 'variantes'])->where('statut', 'disponible')->findOrFail($id);
        return view('catalogue.show', compact('produit'));
    }
}

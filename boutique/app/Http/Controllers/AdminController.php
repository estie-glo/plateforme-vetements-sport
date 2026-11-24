<?php
namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function garde(): void
    {
        abort_unless(Auth::check() && Auth::user()->estAdmin(), 403, 'Accès réservé à l\'administrateur.');
    }

    public function produits()
    {
        $this->garde();
        $produits = Produit::with('variantes')->orderByDesc('id')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('admin.produits', compact('produits', 'categories'));
    }

    public function produitStore(Request $r)
    {
        $this->garde();
        $d = $r->validate([
            'nom' => 'required|max:150',
            'description' => 'nullable',
            'prix' => 'required|integer|min:0',
            'sport' => 'nullable|max:100',
            'categorie_id' => 'required|exists:categorie,id',
        ], ['required' => 'Ce champ est obligatoire.', 'integer' => 'Valeur numérique attendue.']);
        Produit::create(array_merge($d, ['statut' => 'brouillon', 'vendeur_id' => Auth::id()]));
        return back()->with('ok', 'Produit créé au statut « brouillon ». Ajoute ses variantes puis publie-le.');
    }

    public function produitStatut(Request $r, $id)
    {
        $this->garde();
        $d = $r->validate(['statut' => 'required|in:brouillon,disponible,retire']);
        Produit::findOrFail($id)->update(['statut' => $d['statut']]);
        return back()->with('ok', 'Statut du produit mis à jour.');
    }

    public function varianteStore(Request $r, $id)
    {
        $this->garde();
        $produit = Produit::findOrFail($id);
        $d = $r->validate([
            'taille' => 'nullable|max:20',
            'couleur' => 'nullable|max:50',
            'stock' => 'required|integer|min:0',
            'reference_sku' => 'nullable|max:100|unique:variante,reference_sku',
            'prix_specifique' => 'nullable|integer|min:0',
        ], ['reference_sku.unique' => 'Cette référence SKU est déjà utilisée.', 'required' => 'Ce champ est obligatoire.']);
        $produit->variantes()->create($d);
        return back()->with('ok', 'Variante ajoutée.');
    }

    public function varianteStock(Request $r, $id)
    {
        $this->garde();
        $d = $r->validate(['stock' => 'required|integer|min:0']);
        Variante::findOrFail($id)->update(['stock' => $d['stock']]);
        return back()->with('ok', 'Stock mis à jour.');
    }

    public function commandes(Request $r)
    {
        $this->garde();
        $statut = (string) $r->query('statut', '');
        $req = Commande::with(['lignes', 'client', 'adresse'])->orderByDesc('id');
        if ($statut !== '') $req->where('statut', $statut);
        $commandes = $req->get();
        $suivant = ['payee' => 'en_preparation', 'en_preparation' => 'expediee', 'expediee' => 'livree'];
        return view('admin.commandes', compact('commandes', 'statut', 'suivant'));
    }

    public function commandeStatut(Request $r, $id)
    {
        $this->garde();
        $d = $r->validate(['statut' => 'required|in:en_preparation,expediee,livree']);
        $suivant = ['payee' => 'en_preparation', 'en_preparation' => 'expediee', 'expediee' => 'livree'];
        $c = Commande::findOrFail($id);
        if (($suivant[$c->statut] ?? null) !== $d['statut']) {
            return back()->withErrors(['statut' => 'Changement refusé : les statuts avancent dans l\'ordre (payée, en préparation, expédiée, livrée).']);
        }
        $c->update(['statut' => $d['statut']]);
        return back()->with('ok', 'Commande ' . $c->numero . ' : statut « ' . $c->libelle . ' ».');
    }
}

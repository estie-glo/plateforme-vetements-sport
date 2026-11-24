<?php
namespace App\Http\Controllers;

use App\Models\LignePanier;
use App\Models\Panier;
use App\Models\Variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    private function panier(): Panier
    {
        return Panier::firstOrCreate(['utilisateur_id' => Auth::id(), 'statut' => 'actif']);
    }

    public function index()
    {
        $panier = $this->panier();
        $lignes = LignePanier::with('variante.produit')->where('panier_id', $panier->id)->get();
        $total = $lignes->sum(fn ($l) => $l->variante->prix_final * $l->quantite);
        return view('panier.index', compact('lignes', 'total'));
    }

    public function ajouter(Request $r)
    {
        $d = $r->validate([
            'variante_id' => 'required|exists:variante,id',
            'quantite' => 'nullable|integer|min:1',
        ], [
            'variante_id.required' => 'Choisis une taille et une couleur.',
            'variante_id.exists' => 'Cette variante est introuvable.',
            'quantite.integer' => 'Quantité invalide.',
            'quantite.min' => 'La quantité doit être au moins 1.',
        ]);

        $qte = (int) ($d['quantite'] ?? 1);
        $variante = Variante::findOrFail($d['variante_id']);
        $ligne = LignePanier::firstOrNew(['panier_id' => $this->panier()->id, 'variante_id' => $variante->id]);
        $nouvelle = ($ligne->exists ? $ligne->quantite : 0) + $qte;

        if ($nouvelle > $variante->stock) {
            return back()->withErrors(['quantite' => "Stock insuffisant : il reste {$variante->stock} article(s) disponible(s)."]);
        }
        $ligne->quantite = $nouvelle;
        $ligne->save();
        return redirect()->route('panier.index')->with('ok', 'Article ajouté au panier.');
    }

    public function modifier(Request $r, $id)
    {
        $ligne = LignePanier::with('variante')->where('panier_id', $this->panier()->id)->findOrFail($id);
        $d = $r->validate(['quantite' => 'required|integer|min:1'], [
            'quantite.required' => 'Indique une quantité.',
            'quantite.integer' => 'Quantité invalide.',
            'quantite.min' => 'La quantité doit être au moins 1.',
        ]);
        if ($d['quantite'] > $ligne->variante->stock) {
            return back()->withErrors(['quantite' => "Stock insuffisant : il reste {$ligne->variante->stock} article(s) disponible(s)."]);
        }
        $ligne->update(['quantite' => $d['quantite']]);
        return back()->with('ok', 'Quantité mise à jour.');
    }

    public function supprimer($id)
    {
        LignePanier::where('panier_id', $this->panier()->id)->where('id', $id)->delete();
        return back()->with('ok', 'Article retiré du panier.');
    }
}

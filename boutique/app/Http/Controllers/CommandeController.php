<?php
namespace App\Http\Controllers;

use App\Models\Adresse;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\LignePanier;
use App\Models\Panier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CommandeController extends Controller
{
    // Règle de calcul des frais (hypothèse de l'équipe) : 1 000 FCFA à Ambam, 2 500 FCFA ailleurs
    private function frais(string $ville): int
    {
        return mb_strtolower(trim($ville)) === 'ambam' ? 1000 : 2500;
    }

    private function lignesPanier()
    {
        $panier = Panier::where('utilisateur_id', Auth::id())->where('statut', 'actif')->first();
        if (!$panier) return collect();
        return LignePanier::with('variante.produit')->where('panier_id', $panier->id)->get();
    }

    public function recap(Request $r)
    {
        $lignes = $this->lignesPanier();
        if ($lignes->isEmpty()) {
            return redirect()->route('panier.index')->withErrors(['panier' => 'Ton panier est vide : ajoute un article avant de valider.']);
        }
        $adresses = Adresse::where('utilisateur_id', Auth::id())->orderByDesc('est_principale')->get();
        if ($adresses->isEmpty()) {
            return redirect()->route('profil')->withErrors(['adresse' => 'Ajoute une adresse de livraison avant de valider ta commande.']);
        }
        $adresse = $adresses->firstWhere('id', (int) $r->query('adresse_id')) ?? $adresses->first();
        $sousTotal = $lignes->sum(fn ($l) => $l->variante->prix_final * $l->quantite);
        $frais = $this->frais($adresse->ville);
        $total = $sousTotal + $frais;
        return view('commande.recap', compact('lignes', 'adresses', 'adresse', 'sousTotal', 'frais', 'total'));
    }

    public function valider(Request $r)
    {
        $r->validate(['adresse_id' => 'required|exists:adresse,id'], ['adresse_id.required' => 'Choisis une adresse de livraison.']);
        $adresse = Adresse::where('utilisateur_id', Auth::id())->findOrFail($r->input('adresse_id'));

        $commande = DB::transaction(function () use ($adresse) {
            $lignes = $this->lignesPanier();
            if ($lignes->isEmpty()) return null;

            foreach ($lignes as $l) {
                if ($l->quantite > $l->variante->stock) {
                    throw ValidationException::withMessages(['panier' => "Stock insuffisant pour « {$l->variante->produit->nom} » : il reste {$l->variante->stock} article(s)."]);
                }
            }
            $sousTotal = $lignes->sum(fn ($l) => $l->variante->prix_final * $l->quantite);
            $frais = $this->frais($adresse->ville);

            $c = Commande::create([
                'numero' => 'CMD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'utilisateur_id' => Auth::id(),
                'adresse_id' => $adresse->id,
                'statut' => 'en_attente',
                'montant_total' => $sousTotal + $frais,
                'frais_livraison' => $frais,
            ]);
            foreach ($lignes as $l) {
                $prix = $l->variante->prix_final;
                LigneCommande::create([
                    'commande_id' => $c->id,
                    'variante_id' => $l->variante_id,
                    'nom_produit' => $l->variante->produit->nom,
                    'taille' => $l->variante->taille,
                    'couleur' => $l->variante->couleur,
                    'prix_unitaire' => $prix,
                    'quantite' => $l->quantite,
                    'sous_total' => $prix * $l->quantite,
                ]);
            }
            LignePanier::whereIn('id', $lignes->pluck('id'))->delete();
            return $c;
        });

        if (!$commande) {
            return redirect()->route('panier.index')->withErrors(['panier' => 'Ton panier est vide.']);
        }
        return redirect()->route('commandes.show', $commande->id)->with('ok', 'Commande créée : ' . $commande->numero . '. Tu peux maintenant la payer.');
    }

    public function index()
    {
        $commandes = Commande::where('utilisateur_id', Auth::id())->orderByDesc('id')->get();
        return view('commande.index', compact('commandes'));
    }

    public function show($id)
    {
        $commande = Commande::with(['lignes', 'adresse'])->where('utilisateur_id', Auth::id())->findOrFail($id);
        return view('commande.show', compact('commande'));
    }
}

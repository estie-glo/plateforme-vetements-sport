<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    private function commande($id): Commande
    {
        return Commande::with('lignes')->where('utilisateur_id', Auth::id())->findOrFail($id);
    }

    public function form($id)
    {
        $commande = $this->commande($id);
        if ($commande->statut !== 'en_attente') {
            return redirect()->route('commandes.show', $commande->id)->withErrors(['paiement' => 'Cette commande est déjà payée.']);
        }
        return view('paiement.form', compact('commande'));
    }

    public function payer(Request $r, $id)
    {
        $commande = $this->commande($id);
        if ($commande->statut !== 'en_attente') {
            return redirect()->route('commandes.show', $commande->id)
                ->withErrors(['paiement' => 'Cette commande est déjà payée : elle ne peut pas être débitée deux fois.']);
        }

        $d = $r->validate([
            'methode' => 'required|in:orange_money,mtn_momo',
            'telephone' => ['required', 'regex:/^(\+?237)?6[0-9]{8}$/'],
        ], [
            'methode.required' => 'Choisis Orange Money ou MTN MoMo.',
            'methode.in' => 'Moyen de paiement invalide.',
            'telephone.required' => 'Saisis ton numéro de téléphone.',
            'telephone.regex' => 'Numéro invalide : saisis 9 chiffres commençant par 6.',
        ]);

        // SIMULATION : un numéro qui se termine par 00 est refusé par « l'opérateur »
        $refuse = str_ends_with($d['telephone'], '00');

        $resultat = DB::transaction(function () use ($commande, $d, $refuse) {
            $paiement = Paiement::create([
                'commande_id' => $commande->id,
                'reference_transaction' => 'SIM-' . strtoupper(Str::random(10)),
                'methode' => $d['methode'],
                'montant' => $commande->montant_total,
                'statut' => 'en_attente',
            ]);

            if ($refuse) {
                $paiement->update(['statut' => 'echoue']);
                return 'refuse';
            }

            // Verrouillage : évite le double paiement et la vente du dernier article à deux clients
            $verrou = Commande::lockForUpdate()->find($commande->id);
            if ($verrou->statut !== 'en_attente') {
                $paiement->update(['statut' => 'echoue']);
                return 'deja_paye';
            }
            foreach ($commande->lignes as $l) {
                $v = Variante::lockForUpdate()->find($l->variante_id);
                if (!$v || $v->stock < $l->quantite) {
                    $paiement->update(['statut' => 'echoue']);
                    return 'rupture';
                }
            }
            foreach ($commande->lignes as $l) {
                Variante::where('id', $l->variante_id)->decrement('stock', $l->quantite);
            }
            $paiement->update(['statut' => 'valide', 'date_paiement' => now()]);
            $verrou->update(['statut' => 'payee']);
            return 'ok';
        });

        if ($resultat === 'ok') {
            // Notification simulée (écrite dans storage/logs/laravel.log)
            Log::info("SMS simulé : paiement de {$commande->montant_total} FCFA reçu pour la commande {$commande->numero}.");
            return redirect()->route('paiement.confirmation', $commande->id);
        }

        $messages = [
            'refuse' => 'Paiement refusé par l\'opérateur. Ta commande est conservée : tu peux réessayer.',
            'deja_paye' => 'Cette commande est déjà payée : elle ne peut pas être débitée deux fois.',
            'rupture' => 'Un article de ta commande n\'est plus disponible. Le paiement n\'a pas été effectué.',
        ];
        return back()->withErrors(['paiement' => $messages[$resultat]])->withInput();
    }

    public function confirmation($id)
    {
        $commande = $this->commande($id);
        if ($commande->statut === 'en_attente') {
            return redirect()->route('paiement.form', $commande->id);
        }
        return view('paiement.confirmation', compact('commande'));
    }
}

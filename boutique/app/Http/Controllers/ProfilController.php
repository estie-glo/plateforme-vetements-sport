<?php
namespace App\Http\Controllers;

use App\Models\Adresse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $adresses = Adresse::where('utilisateur_id', $user->id)->orderByDesc('est_principale')->get();
        return view('profil.show', compact('user', 'adresses'));
    }

    public function update(Request $r)
    {
        $d = $r->validate([
            'nom' => 'required|max:100',
            'prenom' => 'nullable|max:100',
            'telephone' => 'nullable|max:30',
        ], ['required' => 'Ce champ est obligatoire.']);

        User::where('id', Auth::id())->update($d);
        return back()->with('ok', 'Profil mis à jour.');
    }

    public function adresseStore(Request $r)
    {
        $d = $r->validate([
            'nom_destinataire' => 'required|max:150',
            'telephone' => 'required|max:30',
            'quartier' => 'nullable|max:100',
            'ville' => 'required|max:100',
        ], ['required' => 'Ce champ est obligatoire.']);

        $uid = Auth::id();
        $principale = $r->boolean('est_principale') || !Adresse::where('utilisateur_id', $uid)->exists();

        DB::transaction(function () use ($d, $uid, $principale) {
            if ($principale) {
                Adresse::where('utilisateur_id', $uid)->update(['est_principale' => false]);
            }
            Adresse::create(array_merge($d, ['utilisateur_id' => $uid, 'est_principale' => $principale]));
        });

        return back()->with('ok', 'Adresse enregistrée.');
    }
}

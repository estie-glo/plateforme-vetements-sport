<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function formInscription() { return view('auth.inscription'); }
    public function formConnexion() { return view('auth.connexion'); }

    public function inscription(Request $r)
    {
        $d = $r->validate([
            'nom' => 'required|max:100',
            'email' => 'required|email|max:150|unique:utilisateur,email',
            'password' => 'required|min:8',
            'telephone' => 'nullable|max:30',
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email.email' => 'Adresse e-mail invalide.',
            'email.unique' => 'Cet e-mail est déjà utilisé',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = User::create([
            'nom' => $d['nom'],
            'email' => $d['email'],
            'telephone' => $d['telephone'] ?? null,
            'mot_de_passe_hash' => Hash::make($d['password']),
            'role' => 'client',
        ]);

        Auth::login($user);
        $r->session()->regenerate();
        return redirect('/produits')->with('ok', 'Compte créé : bienvenue !');
    }

    public function connexion(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required'], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Adresse e-mail invalide.',
        ]);

        if (Auth::attempt(['email' => $d['email'], 'password' => $d['password']])) {
            $r->session()->regenerate();
            return redirect()->intended('/produits');
        }
        return back()->withErrors(['email' => 'E-mail ou mot de passe incorrect'])->onlyInput('email');
    }

    public function deconnexion(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/produits');
    }
}

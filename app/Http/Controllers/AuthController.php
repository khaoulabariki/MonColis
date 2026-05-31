<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ]);
        }

        if (!$utilisateur->statut) {
            return back()->withErrors([
                'email' => 'Votre compte est désactivé.',
            ]);
        }

        Auth::login($utilisateur);

        return match($utilisateur->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'ecomercant' => redirect()->route('ecomercant.dashboard'),
            'livreur'    => redirect()->route('livreur.dashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
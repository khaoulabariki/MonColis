<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Colis;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    public function store(Request $request, $token)
    {
        $request->validate([
            'note'        => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $colis = Colis::where('token_suivi', $token)->firstOrFail();

        // Vérifier si avis déjà soumis
        if ($colis->avis) {
            return redirect()->route('tracking', $token)
                             ->with('error', 'Vous avez déjà soumis un avis.');
        }

        Avis::create([
            'colis_id'    => $colis->id,
            'note'        => $request->note,
            'commentaire' => $request->commentaire,
            'sentiment'   => null, // sera analysé par IA plus tard
        ]);

        return redirect()->route('tracking', $token)
                         ->with('success', 'Merci pour votre avis !');
    }
}
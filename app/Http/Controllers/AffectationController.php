<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use App\Models\Utilisateur;
use App\Models\Affectation;

class AffectationController extends Controller
{
    /**
     * Afficher la page de gestion des affectations.
     */
    public function index()
    {
        // 🎯 التعديل هنا: كنجيبو فقط الكوليس لي مازال ما تآفيكتاتش (statut = enregistre) 
        // أو لي livreur_id ديالها باقى خاوية (null) باش غير تتآفيكتا تختفي من هاد الطابلو
        $colis = Colis::where('statut', 'enregistre')
                      ->whereNull('livreur_id')
                      ->orderBy('created_at', 'desc')
                      ->get();

        // 2. Récupérer les livreurs actifs
        $livreurs = Utilisateur::where('role', 'livreur')->where('statut', true)->get();

        // 3. Récupérer l'historique des affectations
        $affectationsList = Affectation::with(['colis', 'livreur'])->orderBy('created_at', 'desc')->get();

        // Retourner la vue avec les variables attendues
        return view('admin.affectations.index', compact('colis', 'livreurs', 'affectationsList'));
    }

    /**
     * Traiter l'affectation d'un colis à un livreur.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'livreur_id' => 'required|exists:utilisateurs,id',
        ]);

        $colisActuel = Colis::findOrFail($id);
        
        // هنا الكود كيبدل الـ statut لـ en_cours و كيعطيها الـ livreur_id
        // وبفضل التعديل لي درنا الفوق، هاد الكوليس غاتختفي توماتيكياً من الطابلو فاش الصفحة دير ريفريش
        $colisActuel->update([
            'livreur_id' => $request->livreur_id,
            'statut'     => 'en_cours'
        ]);

        Affectation::create([
            'colis_id' => $colisActuel->id,
            'livreur_id' => $request->livreur_id,
            'date_affectation' => now(),
            'statut' => 'en_cours'
        ]);

        return redirect()->back()->with('success', __('Le colis a été affecté au livreur avec succès !'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColisController extends Controller
{
    // 1. Afficher les colis de l'E-commerçant connecté (🎯 تعديل هاد الدالة)
    public function index()
    {
        // كنجيبو غير الكوليس ديال هاد الإيكوميرصان لي مكونكتي دابا
        $colis = Colis::where('ecommercant_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        // كنديوه نيشان لملف الـ Blade لي كاين ف الدوسي لي وريتيني ف الصورة
        return view('ecommercant.colis.index', compact('colis'));
    }

    // 2. Créer un nouveau colis (🎯 تعديل الدالة باش تولي ترجع لصفحة الـ Blade ماشي JSON)
   public function store(Request $request)
    {
        // 🎯 غانقراو كاع الاحتمالات ديال العنوان باش لي لقاها لارافيل ف الـ Form ياخدها
        $request->validate([
            'nom_destinataire'       => 'required|string|max:255',
            'prenom_destinataire'    => 'required|string|max:255',
            'telephone_destinataire' => 'required|string',
            'prix'                   => 'required|numeric',
        ]);

        // جيناراسيون أوتوماتيكية
        $codeBarre = 'NWS-' . strtoupper(\Illuminate\Support\Str::random(8));
        $tokenSuivi = (string) \Illuminate\Support\Str::uuid();

        // 🎯 كود سحري: كيشوف واش صيفطتي adresse_livraison أو adresse_destinataire أو غير adresse
        $adresse = $request->input('adresse_livraison') 
                ?? $request->input('adresse_destinataire') 
                ?? $request->input('adresse') 
                ?? 'Adresse non spécifiée'; // قيمة احتياطية باش ميعطيش إيرور نهائيا

        \App\Models\Colis::create([
            'code_suivi'             => $codeBarre,
            'nom_destinataire'       => $request->nom_destinataire,
            'prenom_destinataire'    => $request->prenom_destinataire,
            'telephone_destinataire' => $request->telephone_destinataire,
            'adresse_destinataire'   => $adresse, // 🎯 هادي هي لي غاتحل الإيرور دابا
            'poids'                  => $request->poids ?? 1.0, 
            'prix'                   => $request->prix,
            'statut'                 => 'enregistre', 
            'ecommercant_id'         => auth()->id(), 
            'token_suivi'            => $tokenSuivi, 
        ]);

        return redirect()->route('ecommercant.colis.index')->with('success', 'Colis enregistré avec succès !');
    }

    // Assigner un livreur au colis (بقا كيما هو للاحتياط)
    public function assignLivreur(Request $request, $id)
    {
        $request->validate([
            'livreur_id' => 'required|exists:utilisateurs,id'
        ]);

        $colis = Colis::findOrFail($id);
        $colis->update([
            'livreur_id' => $request->livreur_id,
            'statut' => 'en_cours'
        ]);

        $colis->affectations()->create([
            'livreur_id' => $request->livreur_id,
            'date_affectation' => now(),
            'statut' => 'en_cours'
        ]);

        return redirect()->back()->with('success', 'Livreur assigné avec succès !');
    }
}
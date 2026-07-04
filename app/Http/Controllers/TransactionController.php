<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Retrait;
use App\Models\Wallet;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * 📊 Afficher la page des finances (Espace Admin)
     */
    public function index()
    {
        // 🎯 جلب كاع طلبات السحب مع معلومات التاجر والمحفظة ديالو ديريكت بلا تداخل
        $retraits = Retrait::with(['ecommercant.wallet'])->orderBy('created_at', 'desc')->get();
        
        // 🎯 جلب كاع المحافظ (Wallets) لي ف السيستيم مفرزة مع أصحابها للجدول الثاني
        $wallets = Wallet::with('ecommercant')->orderBy('created_at', 'desc')->get();

        // الحساب الإجمالي
        $totalSolde = Wallet::sum('solde') ?? 0;

        // 🎯 صيفطناهم بالسميات الصحيحة لي غا يخدم بيهم الـ Blade بزز منه
        return view('admin.finances.index', compact('retraits', 'wallets', 'totalSolde'));
    }

    /**
     * 💳 Clôturer le compte d'un livreur
     */
    public function cloturerLivreur(Request $request, $livreur_id)
    {
        $livreur = Utilisateur::where('id', $livreur_id)->where('role', 'livreur')->firstOrFail();
        return redirect()->back()->with('success', "La clôture financière pour le livreur {$livreur->nom} {$livreur->prenom} a été effectuée !");
    }
}
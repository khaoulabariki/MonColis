@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">💳 Gestion Financière</h2>
    <p class="text-sm text-slate-500">Consultez votre solde et gérez vos demandes de retrait.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- 💰 CARD 1: SOLDE DU WALLET -->
    <div class="bg-gradient-to-br from-indigo-600 to-slate-900 p-6 rounded-2xl text-white shadow-lg flex flex-col justify-between h-48">
        <div>
            <span class="text-sm text-indigo-200 uppercase font-bold tracking-wider">Mon Solde Actuel</span>
            <h3 class="text-4xl font-black mt-2">{{ number_format($wallet->solde ?? 0, 2) }} DH</h3>
        </div>
        <div class="text-xs text-indigo-200 flex justify-between items-center border-t border-indigo-500/30 pt-3">
            <span>Dernière mise à jour</span>
            <span class="font-bold">Aujourd'hui</span>
        </div>
    </div>

    <!-- 💸 CARD 2: FORMULAIRE DEMANDE DE RETRAIT -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h4 class="text-base font-bold text-slate-800 mb-4"><i class="fas fa-hand-holding-usd text-indigo-500 mr-2"></i>Demander un Retrait</h4>
        
        <form action="/ecomercant/finances/retrait/store" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Montant à retirer (DH)</label>
                <input type="number" name="montant" max="{{ $wallet->solde ?? 0 }}" min="100" placeholder="Minimum 100 DH" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-semibold" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-sm transition">
                Soumettre la demande
            </button>
        </form>
    </div>

</div>

<!-- 📋 TABLEAU: HISTORIQUE DES RETRAITS -->
<div class="mt-8 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
    <h4 class="text-base font-bold text-slate-800 mb-4"><i class="fas fa-history text-slate-500 mr-2"></i>Historique des demandes</h4>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase">
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Montant</th>
                    <th class="py-3 px-4">Statut</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-600">
                @forelse($retraits as $retrait)
                    <tr class="border-b border-slate-50/50 hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 text-xs text-slate-400">{{ $retrait->created_at }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ number_format($retrait->montant, 2) }} DH</td>
                        <td class="py-3 px-4">
                            @if($retrait->statut == 'paye')
                                <span class="bg-green-50 text-green-600 text-xs px-2.5 py-1 rounded-full font-bold">Payé</span>
                            @elseif($retrait->statut == 'en_cours')
                                <span class="bg-amber-50 text-amber-600 text-xs px-2.5 py-1 rounded-full font-bold">En cours</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 text-xs px-2.5 py-1 rounded-full font-bold">Refusé</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-slate-400 text-xs">Aucune demande de retrait effectuée pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
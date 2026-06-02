@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">💰 Gestion Générale des Finances (Table Unique)</h2>
        <p class="text-sm text-slate-500">Flux de trésorerie centralisés wsst la table `wallets` pour les vendeurs et les livreurs.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                    <i class="fas fa-store text-indigo-500"></i> Soldes E-commerçants (À payer)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="p-3">E-commerçant</th>
                            <th class="p-3">Solde dû</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($wallets as $wallet)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-3 font-semibold text-slate-800">{{ $wallet->nom }} {{ $wallet->prenom }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded text-xs font-bold bg-green-50 text-green-700 border border-green-100">{{ number_format($wallet->solde, 2) }} DH</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="p-4 text-center text-slate-400">Aucun portefeuille.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                    <i class="fas fa-shipping-fast text-amber-500"></i> Cash en possession des Livreurs
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="p-3">Livreur</th>
                            <th class="p-3">Cash Collecté</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($wallets_livreurs as $wl)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-3 font-semibold text-slate-800">{{ $wl->nom }} {{ $wl->prenom }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">{{ number_format($wl->solde, 2) }} DH</span></td>
                                <td class="p-3 text-center">
                                    @if($wl->solde > 0)
                                        <form action="{{ url('/admin/finances/livreur/'.$wl->id.'/regler') }}" method="POST" onsubmit="return confirm('Le livreur a-t-il déposé la totalité du cash au bureau ?')">
                                            @csrf
                                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded transition">
                                                Réglé 💰
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 italic text-[10px]">À jour</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-4 text-center text-slate-400">Aucun livreur avec du cash.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-sm text-slate-700 flex items-center gap-2">
                <i class="fas fa-money-check-alt text-emerald-500"></i> Demandes de Retrait d'Argent
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Date Demande</th>
                        <th class="p-4">E-commerçant</th>
                        <th class="p-4">Montant Demandé</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($retraits as $retrait)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-500">{{ \Carbon\Carbon::parse($retrait->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="p-4 font-semibold text-slate-800">{{ $retrait->nom }} {{ $retrait->prenom }}</td>
                            <td class="p-4 font-bold text-slate-700 text-sm">{{ number_format($retrait->montant, 2) }} DH</td>
                            <td class="p-4">
                                @if($retrait->statut == 'en_attente')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100">En attente</span>
                                @elseif($retrait->statut == 'valide')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-100">Validé</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Rejeté</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($retrait->statut == 'en_attente')
                                    <form action="{{ url('/admin/finances/retrait/'.$retrait->id.'/valider') }}" method="POST" onsubmit="return confirm('Valider le paiement ?')">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-md transition shadow-sm">
                                            Valider
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Aucune action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-12 text-center text-slate-400">Aucune demande reçue.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
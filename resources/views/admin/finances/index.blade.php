@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    {{-- 📋 En-tête de la page --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-[10px] font-black text-[#0A4BB3] uppercase tracking-widest block mb-1">Trésorerie & Distribution</span>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fas fa-wallet text-[#0A4BB3]"></i> Gestion de Caisse & Clôtures
            </h2>
            <p class="text-sm text-slate-400 font-medium mt-0.5">Suivi des fonds non récupérés, traitement des retraits et gestion des caisses livreurs.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500 text-base"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl flex items-center gap-2">
            <i class="fas fa-times-circle text-rose-500 text-base"></i> {{ session('error') }}
        </div>
    @endif

    @php
        $colisLivresGlobal = \App\Models\Colis::where('encaissement_admin', false)
            ->whereIn('statut', ['livre', 'Livré', 'livré', 'Livre'])
            ->get();
            
        $colisRetournesGlobal = \App\Models\Colis::where('encaissement_admin', false)
            ->whereIn('statut', ['retourne', 'Retourné', 'retourné'])
            ->get();
            
        $totalColisEnAttente = $colisLivresGlobal->count() + $colisRetournesGlobal->count();
        $cashEnAttenteGlobal = $colisLivresGlobal->sum('prix'); // Cash only from delivered
        $gainsLivreursEnAttente = $totalColisEnAttente * 20; // Commission on both delivered and returned
        $netAdminEnAttente = $cashEnAttenteGlobal - $gainsLivreursEnAttente; 
    @endphp

    {{-- 📊 Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Cash en Circulation</span>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ number_format($cashEnAttenteGlobal, 2) }} DH</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between border-b-4 border-b-emerald-500">
            <div>
                <span class="text-[9px] font-black text-emerald-700 uppercase tracking-widest block mb-1">A Récupérer Admin & Marchands</span>
                <h3 class="text-xl font-black text-emerald-600 tracking-tight">{{ number_format($netAdminEnAttente, 2) }} DH</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between border-b-4 border-b-amber-500">
            <div>
                <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest block mb-1">Commissions Livreurs</span>
                <h3 class="text-xl font-black text-amber-600 tracking-tight">{{ number_format($gainsLivreursEnAttente, 2) }} DH</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Solde Total E-com</span>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ number_format($totalSolde ?? 0, 2) }} DH</h3>
            </div>
        </div>
    </div>

    {{-- ⚠️ Table 1 : Demandes de Retrait --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xl overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-900 text-xl tracking-tight"><i class="fas fa-clock text-amber-500 mr-2"></i>Demandes de Retrait en Attente</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/40 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6">ID</th>
                        <th class="py-5 px-6">E-commerçant</th>
                        <th class="py-5 px-6">Solde Marchand</th>
                        <th class="py-5 px-6">Montant Demandé</th>
                        <th class="py-5 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @php $hasEnAttente = false; @endphp
                    @foreach($retraits as $retrait)
                        @if($retrait->statut === 'en_attente')
                            @php $hasEnAttente = true; @endphp
                            <tr class="hover:bg-amber-50/10 transition">
                                <td class="py-5 px-6 font-mono text-xs text-slate-400">#{{ $retrait->id }}</td>
                                <td class="py-5 px-6 font-bold text-slate-800">
                                    {{ $retrait->ecommercant->nom ?? 'Marchand' }} {{ $retrait->ecommercant->prenom ?? '' }}
                                </td>
                                <td class="py-5 px-6 font-mono text-slate-500">
                                    {{ number_format($retrait->ecommercant && $retrait->ecommercant->wallet ? $retrait->ecommercant->wallet->solde : 0, 2) }} DH
                                </td>
                                <td class="py-5 px-6 font-black text-[#0A4BB3]">{{ number_format($retrait->montant, 2) }} DH</td>
                                <td class="py-5 px-6 text-center">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.finances.valider', $retrait->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="statut" value="valide">
                                            <button type="submit" class="bg-emerald-500 text-white px-3 py-1.5 rounded-xl text-xs font-black uppercase cursor-pointer border-0">Valider</button>
                                        </form>
                                        <form action="{{ route('admin.finances.valider', $retrait->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="statut" value="rejete">
                                            <button type="submit" class="bg-rose-500 text-white px-3 py-1.5 rounded-xl text-xs font-black uppercase cursor-pointer border-0">Rejeter</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if(!$hasEnAttente)
                        <tr><td colspan="5" class="p-10 text-center text-slate-400 font-bold">Aucune demande en attente.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- 👥 Table 2 : Soldes par E-commerçant --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xs overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-black text-slate-900 text-lg tracking-tight"><i class="fas fa-users text-[#0A4BB3] mr-1.5"></i>Soldes par E-commerçant</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[9px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-5">E-commerçant</th>
                        <th class="py-4 px-5 text-right">Solde Actuel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    {{-- 🎯 التعديل الجوهري: اللوب كتخدم بـ $wallets المفرزة توماتيكياً لمنع التكرار --}}
                    @forelse($wallets as $wallet)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-blue-50 text-[#0A4BB3] flex items-center justify-center">
                                    <i class="fas fa-store"></i>
                                </div>
                                {{ $wallet->ecommercant->nom ?? 'Marchand' }} {{ $wallet->ecommercant->prenom ?? '' }}
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-right">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg font-mono text-emerald-600">
                                    {{ number_format($wallet->solde ?? 0, 2) }} DH
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="p-6 text-center text-slate-400">Aucun portefeuille trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 👥 Table 3 : Situation des caisses par Livreur --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-black text-slate-900 text-lg tracking-tight"><i class="fas fa-truck text-rose-500 mr-1.5"></i>Situation des caisses par Livreur</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[9px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-5">Livreur</th>
                        <th class="py-4 px-5 text-center">Colis Non Récupérés</th>
                        <th class="py-4 px-5 text-center text-amber-700">Gain Livreur (20DH)</th>
                        <th class="py-4 px-5 text-center text-rose-700">Total Encaissé</th>
                        <th class="py-4 px-5 text-center text-emerald-700">Dû à l'Admin</th>
                        <th class="py-4 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse(\App\Models\Utilisateur::where('role', 'livreur')->get() as $livreur)
                        @php
                            $colisLivresQuery = \App\Models\Colis::where('livreur_id', $livreur->id)
                                ->whereIn('statut', ['livre', 'Livré', 'livré', 'Livre'])
                                ->where('encaissement_admin', false)
                                ->get();
                                
                            $colisRetournesQuery = \App\Models\Colis::where('livreur_id', $livreur->id)
                                ->whereIn('statut', ['retourne', 'Retourné', 'retourné'])
                                ->where('encaissement_admin', false)
                                ->get();

                            $colisLivreCount = $colisLivresQuery->count() + $colisRetournesQuery->count();
                            $cashEnPoche = $colisLivresQuery->sum('prix'); // L'argent liquide n'est que sur les colis livrés
                            $rba7LivreurActuel = $colisLivreCount * 20; // 20 DH par colis traité (livré ou retourné)
                            $resteAVerserAdmin = $cashEnPoche - $rba7LivreurActuel;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-slate-800">
                                {{ $livreur->nom }} {{ $livreur->prenom }}
                            </td>
                            <td class="py-4 px-5 text-center">{{ $colisLivreCount }} u</td>
                            <td class="py-4 px-5 text-center text-amber-600 font-mono">{{ number_format($rba7LivreurActuel, 2) }} DH</td>
                            <td class="py-4 px-5 text-center text-rose-600 font-mono">{{ number_format($cashEnPoche, 2) }} DH</td>
                            <td class="py-4 px-5 text-center text-emerald-600 font-mono">{{ number_format($resteAVerserAdmin, 2) }} DH</td>
                            <td class="py-4 px-5 text-right">
                                @if($colisLivreCount > 0)
                                    <form action="{{ route('admin.finances.cloturer', $livreur->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="bg-slate-900 text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-md cursor-pointer border-0">Clôturer</button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-emerald-500 font-bold uppercase mr-2"><i class="fas fa-check-circle"></i> À jour</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-6 text-center text-slate-400">Aucun livreur trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
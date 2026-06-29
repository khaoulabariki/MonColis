@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    {{-- 📋 1️⃣ En-tête de la page --}}
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

    @php
        // On calcule UNIQUEMENT les colis livrés dont le cash n'a pas encore été récupéré par l'admin (encaissement_admin = false)
        $colisNonClotures = \App\Models\Colis::where('statut', 'livre')->where('encaissement_admin', false);
        
        $totalColisEnAttente = $colisNonClotures->count();
        $cashEnAttenteGlobal = $colisNonClotures->sum('prix');
        
        // Règle : 50 DH Admin / 20 DH Livreur
        $netAdminEnAttente = $totalColisEnAttente * 50; 
        $gainsLivreursEnAttente = $totalColisEnAttente * 20; 
    @endphp

    {{-- 📊 2️⃣ LES STATISTIQUES DE LA CAISSE ACTUELLE (EN COURS) --}}
    <div class="mb-2">
        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fas fa-chart-pie text-[#0A4BB3]"></i> Situation de la Période Active (Flux non clôturés)
        </h4>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        
        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Cash en Circulation</span>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ number_format($cashEnAttenteGlobal, 2) }} <span class="text-xs font-bold text-slate-400">DH</span>
                </h3>
                <p class="text-[10px] font-medium text-rose-500 mt-1"><i class="fas fa-clock mr-1"></i>Dans les poches des livreurs</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0A4BB3] flex items-center justify-center text-sm shadow-sm">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between border-b-4 border-b-emerald-500">
            <div>
                <span class="text-[9px] font-black text-emerald-700 uppercase tracking-widest block mb-1">A Récupérer par l'Admin (50 DH/u)</span>
                <h3 class="text-xl font-black text-emerald-600 tracking-tight">
                    {{ number_format($netAdminEnAttente, 2) }} <span class="text-xs font-bold text-emerald-400">DH</span>
                </h3>
                <p class="text-[10px] font-medium text-slate-400 mt-1">Gains plateforme à sécuriser</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shadow-sm">
                <i class="fas fa-building-columns"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between border-b-4 border-b-amber-500">
            <div>
                <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest block mb-1">Commissions Livreurs (20 DH/u)</span>
                <h3 class="text-xl font-black text-amber-600 tracking-tight">
                    {{ number_format($gainsLivreursEnAttente, 2) }} <span class="text-xs font-bold text-amber-400">DH</span>
                </h3>
                <p class="text-[10px] font-medium text-slate-400 mt-1">À déduire lors de la clôture</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-sm">
                <i class="fas fa-motorcycle"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Solde Total E-com</span>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ number_format(\App\Models\Wallet::whereNotNull('ecommercant_id')->sum('solde'), 2) }} <span class="text-xs font-bold text-slate-400">DH</span>
                </h3>
                <p class="text-[10px] font-medium text-slate-400 mt-1">Fonds totaux dus aux marchands</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center text-sm shadow-sm">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
        </div>
    </div>

    {{-- ⚠️ 3️⃣ LES DEMANDES DE RETRAIT --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/40 overflow-hidden mb-8">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block mb-1">Flux de Virement</span>
            <h3 class="font-black text-slate-900 text-xl tracking-tight flex items-center gap-2">
                <i class="fas fa-clock text-amber-500"></i> Demandes de Retrait en Attente
            </h3>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Validez ou rejetez les demandes de virement des e-commerçants</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/40 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6">ID</th>
                        <th class="py-5 px-6">E-commerçant</th>
                        <th class="py-5 px-6">Montant Demandé</th>
                        <th class="py-5 px-6">Date de Demande</th>
                        <th class="py-5 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($retraitsAttente as $retrait)
                        @php 
                            $ecommercant = \App\Models\Utilisateur::find($retrait->ecommercant_id); 
                        @endphp
                        <tr class="hover:bg-amber-50/10 transition">
                            <td class="py-5 px-6 font-mono font-black text-slate-400 text-xs">#{{ $retrait->id }}</td>
                            <td class="py-5 px-6 font-bold text-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    {{ $ecommercant->nom ?? 'Marchand' }} {{ $ecommercant->prenom ?? '' }}
                                </div>
                            </td>
                            <td class="py-5 px-6 font-black text-[#0A4BB3] text-base">{{ number_format($retrait->montant, 2) }} DH</td>
                            <td class="py-5 px-6 text-slate-400 text-xs font-mono">{{ $retrait->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-5 px-6 text-center">
                                <div class="flex justify-center items-center gap-2.5">
                                    <form action="{{ route('admin.finances.valider', $retrait->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <input type="hidden" name="statut" value="valide">
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition cursor-pointer shadow-md border-0">
                                            <i class="fas fa-check mr-1.5 text-[10px]"></i> Valider
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.finances.valider', $retrait->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <input type="hidden" name="statut" value="rejete">
                                        <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition cursor-pointer shadow-md border-0">
                                            <i class="fas fa-times mr-1.5 text-[10px]"></i> Rejeter
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">
                                <i class="fas fa-check-circle text-emerald-500 text-2xl block mb-2"></i> Aucune demande de retrait en attente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 👥 4️⃣ SOLDES DES E-COMMERÇANTS (Prend toute la ligne) --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xs overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Portefeuilles Marchands</span>
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
                    @forelse(\App\Models\Utilisateur::where('role', 'ecommercant')->get() as $ecom)
                        @php 
                            $userWallet = \App\Models\Wallet::where('ecommercant_id', $ecom->id)->first(); 
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-blue-50 text-[#0A4BB3] flex items-center justify-center text-[10px]">
                                    <i class="fas fa-store"></i>
                                </div>
                                {{ $ecom->nom }} {{ $ecom->prenom }}
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-right">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg font-mono">
                                    {{ number_format($userWallet->solde ?? 0, 2) }} DH
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="p-6 text-center text-slate-400">Aucun e-commerçant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 👥 5️⃣ SITUATION FINANCIERE DES LIVREURS (Prend toute la ligne) --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest block mb-1">Fonds & Distribution (Période Active)</span>
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
                            $colisActifsQuery = \App\Models\Colis::where('livreur_id', $livreur->id)
                                                                 ->where('statut', 'livre')
                                                                 ->where('encaissement_admin', false);
                            
                            $colisLivreCount = $colisActifsQuery->count();
                            $cashEnPoche = $colisActifsQuery->sum('prix');
                            $rba7LivreurActuel = $colisLivreCount * 20; 
                            $resteAVerserAdmin = $cashEnPoche - $rba7LivreurActuel;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-rose-50 text-rose-600 flex items-center justify-center text-[10px]">
                                    <i class="fas fa-user"></i>
                                </div>
                                {{ $livreur->nom }} {{ $livreur->prenom }}
                            </td>
                            <td class="py-4 px-5 text-center font-black text-slate-500">
                                {{ $colisLivreCount }} u
                            </td>
                            <td class="py-4 px-5 text-center font-black text-amber-600">
                                {{ number_format($rba7LivreurActuel, 2) }} DH
                            </td>
                            <td class="py-4 px-5 text-center font-black text-rose-600">
                                {{ number_format($cashEnPoche, 2) }} DH
                            </td>
                            <td class="py-4 px-5 text-center font-black text-emerald-600">
                                {{ number_format($resteAVerserAdmin, 2) }} DH
                            </td>
                            <td class="py-4 px-5 text-right">
                                @if($colisLivreCount > 0)
                                    <form action="{{ route('admin.finances.cloturer', $livreur->id) }}" method="POST" class="m-0 inline-block">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Confirmer la récupération du cash et la clôture de la caisse pour ce livreur ?')" class="bg-slate-900 hover:bg-slate-800 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-md transition cursor-pointer border-0">
                                            Clôturer la caisse
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-300 font-bold uppercase mr-2">À jour</span>
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
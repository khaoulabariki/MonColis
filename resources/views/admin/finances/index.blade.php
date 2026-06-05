@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800"><i class="fas fa-wallet text-indigo-500 mr-2"></i>Suivi des Finances (Admin)</h2>
        <p class="text-sm text-slate-500">Visualisez le chiffre d'affaires, les gains de la plateforme, traitez les retraits et le détail par e-commerçant.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 bg-amber-50/40">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                <i class="fas fa-clock text-amber-500"></i> Demandes de Retrait en Attente
            </h3>
            <p class="text-xs text-slate-400 mt-1">Validez ou rejetez les demandes de virement des e-commerçants</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px]">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">E-commerçant</th>
                        <th class="p-4">Montant Demandé</th>
                        <th class="p-4">Date de Demande</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($retraitsAttente as $retrait)
                        <tr class="hover:bg-amber-50/10 transition">
                            <td class="p-4 font-bold text-slate-700">#{{ $retrait->id }}</td>
                            <td class="p-4 font-medium text-slate-800">
                                @php
                                    $ecommercant = \App\Models\Utilisateur::find($retrait->ecommercant_id);
                                @endphp
                                <i class="fas fa-store text-slate-400 mr-2"></i>{{ $ecommercant->nom ?? 'Marchand' }} {{ $ecommercant->prenom ?? '' }}
                            </td>
                            <td class="p-4 font-bold text-indigo-600 text-base">{{ number_format($retrait->montant, 2) }} DH</td>
                            <td class="p-4 text-slate-500 text-xs">{{ $retrait->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-center space-x-2">
                                
                                <form action="{{ route('admin.finances.valider', $retrait->id) }}" method="POST" class="inline-block m-0">
                                    @csrf
                                    <input type="hidden" name="statut" value="valide">
                                    <button type="submit" class="bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-600 transition cursor-pointer shadow-sm">
                                        <i class="fas fa-check mr-1"></i> Valider
                                    </button>
                                </form>

                                <form action="{{ route('admin.finances.valider', $retrait->id) }}" method="POST" class="inline-block m-0">
                                    @csrf
                                    <input type="hidden" name="statut" value="rejete">
                                    <button type="submit" class="bg-rose-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-rose-600 transition cursor-pointer shadow-sm">
                                        <i class="fas fa-times mr-1"></i> Rejeter
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-xs">
                                <i class="fas fa-check-circle text-emerald-400 text-lg mb-1 block"></i>
                                Aucune demande de retrait en attente pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Collecté (Cash)</span>
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fas fa-money-bill-wave"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">
                {{ number_format(\App\Models\Colis::where('statut', 'livre')->sum('prix'), 2) }} DH
            </h3>
            <p class="text-[10px] text-slate-400 mt-1">Total du cash actuellement chez les livreurs</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gains Plateforme (Les Frais)</span>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fas fa-chart-line"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">
                {{ number_format(\App\Models\Colis::where('statut', 'livre')->count() * 50, 2) }} DH
            </h3>
            <p class="text-[10px] text-emerald-600 mt-1">Calculé sur la base de 50 DH de frais par livraison</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Solde E-commerçants</span>
                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fas fa-hand-holding-usd"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">
                {{ number_format(\App\Models\Wallet::whereNotNull('ecommercant_id')->sum('solde'), 2) }} DH
            </h3>
            <p class="text-[10px] text-slate-400 mt-1">Total des fonds globaux dus aux e-commerçants</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 bg-amber-50/30">
            <h3 class="font-bold text-slate-800 text-base"><i class="fas fa-users text-amber-500 mr-2"></i>Détail des Soldes par E-commerçant</h3>
            <p class="text-xs text-slate-400 mt-1">Consultez le portefeuille nominatif de chaque vendeur</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px]">
                    <tr>
                        <th class="p-4">Nom de l'E-commerçant</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4 text-right">Solde Actuel (Portefeuille)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(\App\Models\Utilisateur::where('role', 'ecommercant')->get() as $ecom)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-semibold text-slate-700">
                                <i class="fas fa-user-circle text-slate-400 mr-2"></i>{{ $ecom->nom }} {{ $ecom->prenom }}
                            </td>
                            <td class="p-4 text-slate-500 text-xs">{{ $ecom->email }}</td>
                            <td class="p-4 text-slate-500 text-xs">{{ $ecom->telephone ?? 'Non renseigné' }}</td>
                            <td class="p-4 font-bold text-amber-600 text-right text-base">
                                @php
                                    $userWallet = \App\Models\Wallet::where('ecommercant_id', $ecom->id)->first();
                                @endphp
                                {{ number_format($userWallet->solde ?? 0, 2) }} DH
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 text-xs">Aucun e-commerçant trouvé dans le système.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Détails des Colis Livrés (Données Réelles)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px]">
                    <tr>
                        <th class="p-4">Code Colis</th>
                        <th class="p-4">E-commerçant</th>
                        <th class="p-4">Prix du Colis</th>
                        <th class="p-4">Frais Livraison</th>
                        <th class="p-4">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(\App\Models\Colis::where('statut', 'livre')->orderBy('updated_at', 'desc')->get() as $colisReal)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-semibold text-indigo-600">#{{ $colisReal->code_suivi }}</td>
                            <td class="p-4 font-medium text-slate-700">
                                @php
                                    $owner = \App\Models\Utilisateur::find($colisReal->ecommercant_id);
                                @endphp
                                @if($owner)
                                    {{ $owner->nom }} {{ $owner->prenom }}
                                @else
                                    <span class="text-xs text-slate-400 italic">Non spécifié</span>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-slate-800">{{ number_format($colisReal->prix, 2) }} DH</td>
                            <td class="p-4 text-rose-500 font-medium">-50.00 DH</td>
                            <td class="p-4"><span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Livré</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-xs">Aucun colis marqué comme "Livré" pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
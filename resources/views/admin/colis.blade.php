@extends('layouts.app')

@section('content')
    <!-- 🌟 En-tête -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">📦 Gestion des Colis</h2>
            <p class="text-sm text-slate-500">Suivi, filtrage et modification des colis enregistrés.</p>
        </div>
        <!-- Bouton pour créer un nouveau colis (ila bghitih mn b3d) -->
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-md shadow-indigo-600/10 flex items-center gap-2">
            <i class="fas fa-plus"></i> Ajouter un Colis
        </button>
    </div>

    <!-- 📊 TABLEAU DES COLIS -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Code Suivi</th>
                        <th class="p-4">Destinataire</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4">Ville / Adresse</th>
                        <th class="p-4">Poids</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($colisList as $colis)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Code Suivi -->
                            <td class="p-4 font-mono font-semibold text-slate-700">
                                #{{ $colis->code_suivi }}
                            </td>
                            <!-- Nom & Prénom -->
                            <td class="p-4 font-medium text-slate-800">
                                {{ $colis->nom_destinataire }} {{ $colis->prenom_destinataire }}
                            </td>
                            <!-- Téléphone -->
                            <td class="p-4 text-slate-500">
                                {{ $colis->telephone_destinataire }}
                            </td>
                            <!-- Adresse -->
                            <td class="p-4">
                                <span class="block text-xs text-slate-400 max-w-[180px] truncate" title="{{ $colis->adresse_destinataire }}">
                                    {{ $colis->adresse_destinataire }}
                                </span>
                            </td>
                            <!-- Poids -->
                            <td class="p-4 font-medium text-slate-700">
                                {{ $colis->poids }} kg
                            </td>
                            <!-- Prix -->
                            <td class="p-4 font-bold text-slate-900">
                                {{ number_format($colis->prix, 2) }} DH
                            </td>
                            <!-- Statut (Badges dynamic b l-alwan) -->
                            <td class="p-4">
                                @if($colis->statut == 'enregistre')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100">Enregistré</span>
                                @elseif($colis->statut == 'ramasse')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-600 border border-purple-100">Ramassé</span>
                                @elseif($colis->statut == 'en_cours')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100">En cours</span>
                                @elseif($colis->statut == 'livre')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600 border border-green-100">Livré</span>
                                @elseif($colis->statut == 'retourne')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-100">Retourné</span>
                                @elseif($colis->statut == 'annule')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Annulé</span>
                                @endif
                            </td>
                            <!-- Actions -->
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-1.5 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Modifier">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button class="p-1.5 bg-slate-50 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition" title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Message khta fach d la table makhawyach -->
                        <tr>
                            <td colspan="8" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-box-open text-3xl text-slate-300"></i>
                                    <p class="text-sm">Aucun colis trouvé dans la base de données.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
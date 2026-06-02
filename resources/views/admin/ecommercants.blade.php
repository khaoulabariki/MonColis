@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">🏪 Gestion des E-commerçants</h2>
            <p class="text-sm text-slate-500">Visualisez la liste des vendeurs, leurs coordonnées et l'état de leurs comptes.</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-md shadow-indigo-600/10 flex items-center gap-2">
            <i class="fas fa-store-alt"></i> Ajouter un E-commerçant
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">E-commerçant</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4">Rôle</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4">Date de Partenariat</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ecommercants as $ecom)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-medium text-slate-800 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold uppercase text-xs">
                                    {{ substr($ecom->nom, 0, 1) }}{{ substr($ecom->prenom, 0, 1) }}
                                </div>
                                <div>
                                    <span class="block font-semibold text-slate-800">{{ $ecom->nom }} {{ $ecom->prenom }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-slate-600 font-medium">
                                {{ $ecom->email }}
                            </td>
                            <td class="p-4 text-slate-500">
                                {{ $ecom->telephone ?? 'Non renseigné' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    <i class="fas fa-store mr-1"></i> E-commerçant
                                </span>
                            </td>
                            <td class="p-4">
                                @if($ecom->statut)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Suspendu
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-slate-400">
                                {{ $ecom->created_at ? $ecom->created_at->format('d/m/Y') : '--/--/----' }}
                            </td>
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
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-store-alt-slash text-3xl text-slate-300"></i>
                                    <p class="text-sm">Aucun e-commerçant inscrit pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
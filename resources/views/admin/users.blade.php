@extends('layouts.app')

@section('content')
    <!-- 🌟 En-tête -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">👥 Gestion des Utilisateurs</h2>
            <p class="text-sm text-slate-500">Visualisez et gérez les comptes des administrateurs, e-commerçants et livreurs.</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-md shadow-indigo-600/10 flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Ajouter un Utilisateur
        </button>
    </div>

    <!-- 📊 TABLEAU DES UTILISATEURS -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Utilisateur</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4">Rôle</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4">Date d'inscription</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($utilisateurs as $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Nom & Prénom -->
                            <td class="p-4 font-medium text-slate-800 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold uppercase text-xs">
                                    {{ substr($user->nom, 0, 1) }}{{ substr($user->prenom, 0, 1) }}
                                </div>
                                <div>
                                    <span class="block font-semibold text-slate-800">{{ $user->nom }} {{ $user->prenom }}</span>
                                </div>
                            </td>
                            <!-- Email -->
                            <td class="p-4 text-slate-600 font-medium">
                                {{ $user->email }}
                            </td>
                            <!-- Téléphone -->
                            <td class="p-4 text-slate-500">
                                {{ $user->telephone ?? 'Non renseigné' }}
                            </td>
                            <!-- Rôle -->
                            <td class="p-4">
                                @if($user->role == 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                        <i class="fas fa-user-shield mr-1"></i> Admin
                                    </span>
                                @elseif($user->role == 'ecomercant')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        <i class="fas fa-store mr-1"></i> E-commerçant
                                    </span>
                                @elseif($user->role == 'livreur')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                        <i class="fas fa-truck mr-1"></i> Livreur
                                    </span>
                                @endif
                            </td>
                            <!-- Statut -->
                            <td class="p-4">
                                @if($user->statut)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactif
                                    </span>
                                @endif
                            </td>
                            <!-- Date -->
                            <td class="p-4 text-xs text-slate-400">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '--/--/----' }}
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
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-users-slash text-3xl text-slate-300"></i>
                                    <p class="text-sm">Aucun utilisateur trouvé.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
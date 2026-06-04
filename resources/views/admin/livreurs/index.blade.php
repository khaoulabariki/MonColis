@extends('layouts.app') {{-- Structure globale intacte --}}

@section('content')
<div class="container-fluid my-6">
    
    {{-- Affichage des messages de succès ou d'erreur --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/10 text-green-500 border border-green-500/20 rounded-lg text-sm font-medium">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-500/10 text-red-500 border border-red-500/20 rounded-lg text-sm font-medium">
            <i class="fas fa-exclamation-circle mr-2"></i> Erreur lors de l'enregistrement. Vérifiez les informations.
        </div>
    @endif
    
    {{-- En-tête de la page --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800"><i class="fas fa-truck text-indigo-500 mr-2"></i>Gestion des Livreurs</h2>
            <p class="text-sm text-slate-500">Gérez les comptes des livreurs et suivez leurs informations personnelles.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- 1️⃣ FORMULAIRE D'AJOUT D'UN NOUVEAU LIVREUR --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 h-fit">
            <h3 class="font-bold text-slate-800 text-lg mb-4"><i class="fas fa-user-plus text-indigo-500 mr-2"></i>Ajouter un Livreur</h3>
            
            <form action="{{ route('admin.livreurs.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nom</label>
                    <input type="text" name="nom" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Prénom</label>
                    <input type="text" name="prenom" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Email / Identifiant</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Mot de passe</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Téléphone</label>
                    <input type="text" name="telephone" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg text-sm transition shadow-sm">
                    Enregistrer le livreur
                </button>
            </form>
        </div>

        {{-- 2️⃣ TABLEAU DE LA LISTE DES LIVREURS EXISTANTS --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Liste des Livreurs</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Nom Complet</th>
                            <th class="p-4">Email</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- CORRECTION ICI : Utilisation de $livreursList au lieu de $livreurs --}}
                        @forelse($livreursList ?? [] as $livreur)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 font-mono text-slate-400">#{{ $livreur->id }}</td>
                                <td class="p-4 font-medium text-slate-800">{{ $livreur->nom }} {{ $livreur->prenom }}</td>
                                <td class="p-4">{{ $livreur->email }}</td>
                                <td class="p-4 text-center flex justify-center items-center gap-3">
                                    
                                    {{-- Formulaire sécurisé pour supprimer un livreur --}}
                                    <form action="{{ route('admin.livreurs.destroy', $livreur->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livreur ?');" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition bg-transparent border-0 p-0 cursor-pointer align-middle">
                                            <i class="fas fa-trash-alt" title="Supprimer"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400">Aucun livreur trouvé dans le système.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
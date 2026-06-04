@extends('layouts.app') {{-- Structure globale intacte --}}

@section('content')

    {{-- CONDITION : Si le mode est 'creation', on affiche le formulaire (Sert pour l'ajout et la modification) --}}
    @if(isset($mode) && $mode === 'creation')
        
        {{-- BLOC DU FORMULAIRE DYNAMIQUE (AJOUT / MODIFICATION) --}}
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8 my-6">
            <div class="mb-6">
                <a href="{{ route('admin.colis.index') }}" class="text-indigo-600 text-xs font-semibold hover:underline">&larr; Retour à la liste</a>
                
                {{-- Titre dynamique selon le contexte --}}
                <h1 class="text-2xl font-bold text-gray-800 mt-2">
                    {{ isset($colisEnCours) && $colisEnCours ? 'Modifier le Colis' : 'Ajouter un Nouveau Colis' }}
                </h1>
            </div>

            {{-- Formulaire sécurisé d'enregistrement/mise à jour --}}
            <form action="{{ route('admin.colis.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Code Suivi</label>
                        {{-- Code suivi généré ou existant en mode lecture seule --}}
                        <input type="text" name="code_barre" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->code_suivi : 'TRK-'.rand(100000, 999999) }}" 
                               readonly required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 font-mono text-gray-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">E-commerçant (Propriétaire)</label>
                        {{-- CORRECTION : Le name doit avoir 2 'm' pour correspondre à la validation et la base de données --}}
                        <select name="ecommercant_id" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-indigo-500">
                            <option value="">Sélectionner un client</option>
                            @foreach($ecommercants as $eco)
                                <option value="{{ $eco->id }}" {{ isset($colisEnCours) && $colisEnCours && $colisEnCours->ecommercant_id == $eco->id ? 'selected' : '' }}>
                                    {{ $eco->nom }} {{ $eco->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nom du Destinataire</label>
                        <input type="text" name="destinataire" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->nom_destinataire : '' }}" 
                               required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Prénom du Destinataire</label>
                        <input type="text" name="prenom_destinataire" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->prenom_destinataire : '' }}" 
                               required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Téléphone Destinataire</label>
                        <input type="text" name="telephone" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->telephone_destinataire : '' }}" 
                               required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Ville / Adresse de Destination</label>
                        <input type="text" name="ville" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->adresse_destinataire : '' }}" 
                               required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Prix (DH)</label>
                        <input type="number" step="0.01" name="prix" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->prix : '' }}" 
                               required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50 flex justify-end space-x-3">
                    <a href="{{ route('admin.colis.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition">Annuler</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        {{ isset($colisEnCours) && $colisEnCours ? 'Mettre à jour' : 'Enregistrer le colis' }}
                    </button>
                </div>
            </form>
        </div>

    {{-- SINON : Si on est en mode normal (liste), on affiche le tableau complet --}}
    @else
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden my-6">
            
            {{-- En-tête avec le bouton Ajouter un Colis --}}
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800">Liste des Colis</h2>
                <a href="{{ route('admin.colis.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-md shadow-indigo-600/10 flex items-center gap-2 decoration-none">
                    <i class="fas fa-box-open"></i> Ajouter un Colis
                </a>
            </div>

            {{-- Affichage des messages flash de succès --}}
            @if(session('success'))
                <div class="m-6 p-4 bg-green-50 border border-green-200 text-green-600 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="p-4">Code Suivi</th>
                            <th class="p-4">Destinataire</th>
                            <th class="p-4">Téléphone</th>
                            <th class="p-4">Ville / Adresse</th>
                            <th class="p-4">Prix</th>
                            <th class="p-4">Livreur</th>
                            <th class="p-4">Statut</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($colisList as $colis)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 font-mono font-semibold text-indigo-600">{{ $colis->code_suivi }}</td>
                                <td class="p-4 font-medium text-slate-800">{{ $colis->nom_destinataire }} {{ $colis->prenom_destinataire }}</td>
                                <td class="p-4">{{ $colis->telephone_destinataire }}</td>
                                <td class="p-4">{{ $colis->adresse_destinataire }}</td>
                                <td class="p-4 font-semibold text-slate-700">{{ $colis->prix }} DH</td>
                                
                                {{-- CORRECTION : Utilisation de la relation 'livreur' définie dans le controller web.php --}}
                                <td class="p-4">
                                    @if($colis->livreur)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100">
                                            {{ $colis->livreur->nom }} {{ $colis->livreur->prenom }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">
                                            Non affecté
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                        {{ $colis->statut }}
                                    </span>
                                </td>
                                <td class="p-4 text-center flex justify-center items-center gap-3">
                                    {{-- Lien vers la route de modification --}}
                                    <a href="{{ route('admin.colis.edit', $colis->id) }}" class="text-slate-400 hover:text-indigo-600 transition">
                                        <i class="fas fa-edit" title="Modifier"></i>
                                    </a>

                                    {{-- Formulaire sécurisé pour la suppression définitive --}}
                                    <form action="{{ route('admin.colis.destroy', $colis->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce colis ?');" class="inline m-0 p-0">
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
                                <td colspan="8" class="p-8 text-center text-gray-400">Aucun colis trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
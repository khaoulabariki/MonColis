@extends('layouts.app') {{-- Structure globale intacte --}}

@section('content')

    {{-- CONDITION : Si le mode est 'creation', on affiche le formulaire (Sert pour l'ajout et la modification) --}}
    @if(isset($mode) && $mode === 'creation')
        
        {{-- BLOC DU FORMULAIRE DYNAMIQUE (AJOUT / MODIFICATION) --}}
        <div class="max-w-3xl mx-auto bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/50 p-8 my-6">
            <div class="mb-8">
                <a href="{{ route('admin.colis.index') }}" class="text-[#0A4BB3] text-xs font-black tracking-wider uppercase hover:underline flex items-center gap-1">
                    <i class="fas fa-arrow-left text-[10px]"></i> Retour à la liste
                </a>
                
                {{-- Titre dynamique selon le contexte --}}
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-3">
                    {{ isset($colisEnCours) && $colisEnCours ? 'Modifier le Colis' : 'Ajouter un Nouveau Colis' }}
                </h1>
                <p class="text-xs font-medium text-slate-400 mt-0.5">Renseignez les informations requises pour le traitement du colis.</p>
            </div>

            {{-- Formulaire sécurisé d'enregistrement/mise à jour --}}
            <form action="{{ route('admin.colis.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Code Suivi</label>
                        {{-- Code suivi généré ou existant en mode lecture seule --}}
                        <input type="text" name="code_barre" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->code_suivi : 'TRK-'.rand(100000, 999999) }}" 
                               readonly required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 font-mono text-slate-600 outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">E-commerçant (Propriétaire)</label>
                        <select name="ecommercant_id" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm bg-white text-slate-700 font-semibold focus:outline-none transition">
                            <option value="">Sélectionner un client</option>
                            @foreach($ecommercants as $eco)
                                <option value="{{ $eco->id }}" {{ isset($colisEnCours) && $colisEnCours && $colisEnCours->ecommercant_id == $eco->id ? 'selected' : '' }}>
                                    {{ $eco->nom }} {{ $eco->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nom du Destinataire</label>
                        <input type="text" name="destinataire" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->nom_destinataire : '' }}" 
                               required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prénom du Destinataire</label>
                        <input type="text" name="prenom_destinataire" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->prenom_destinataire : '' }}" 
                               required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Téléphone Destinataire</label>
                        <input type="text" name="telephone" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->telephone_destinataire : '' }}" 
                               required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ville / Adresse de Destination</label>
                        <input type="text" name="ville" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->adresse_destinataire : '' }}" 
                               required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prix (DH)</label>
                        <input type="number" step="0.01" name="prix" 
                               value="{{ isset($colisEnCours) && $colisEnCours ? $colisEnCours->prix : '' }}" 
                               required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-bold text-slate-800 focus:outline-none transition">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ route('admin.colis.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-wider rounded-xl transition">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 bg-[#0A4BB3] hover:bg-[#083da3] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-lg shadow-blue-900/10">
                        {{ isset($colisEnCours) && $colisEnCours ? 'Mettre à jour' : 'Enregistrer le colis' }}
                    </button>
                </div>
            </form>
        </div>

    {{-- SINON : Si on est en mode normal (liste), on affiche le tableau complet --}}
    @else
        
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Gestion des expéditions</span>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    Suivi des Colis
                </h1>
            </div>
            <a href="{{ route('admin.colis.create') }}" class="bg-[#0A4BB3] hover:bg-[#083da3] text-white text-xs font-black uppercase tracking-widest px-5 py-3 rounded-2xl transition shadow-lg shadow-blue-900/10 flex items-center gap-2.5 decoration-none">
                <i class="fas fa-plus text-[10px]"></i> Ajouter un Colis
            </a>
        </div>

        {{-- Affichage des messages flash de succès --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200/60 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm text-slate-600">
                    <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="py-5 px-6">Code Suivi</th>
                            <th class="py-5 px-6">Destinataire</th>
                            <th class="py-5 px-6">Téléphone</th>
                            <th class="py-5 px-6">Ville / Adresse</th>
                            <th class="py-5 px-6">Prix</th>
                            <th class="py-5 px-6">Livreur</th>
                            <th class="py-5 px-6">Statut</th>
                            <th class="py-5 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($colisList as $colis)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-5 px-6 font-mono font-black text-[#0A4BB3] text-sm">{{ $colis->code_suivi }}</td>
                                <td class="py-5 px-6 font-bold text-slate-800">{{ $colis->nom_destinataire }} {{ $colis->prenom_destinataire }}</td>
                                <td class="py-5 px-6 text-slate-500">{{ $colis->telephone_destinataire }}</td>
                                <td class="py-5 px-6 text-slate-500 max-w-xs truncate">{{ $colis->adresse_destinataire }}</td>
                                <td class="py-5 px-6 font-black text-slate-900">{{ $colis->prix }} DH</td>
                                
                                <td class="py-5 px-6">
                                    @if($colis->livreur)
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/60 flex items-center gap-1.5 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $colis->livreur->nom }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100/60 flex items-center gap-1.5 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Non affecté
                                        </span>
                                    @endif
                                </td>
                                   <td class="py-5 px-6">
    {{-- Statuts premium et bien contrastés par rapport au livreur --}}
    @if(strtoupper($colis->statut) === 'LIVRÉ' || strtoupper($colis->statut) === 'LIVRE' || strtoupper($colis->statut) === 'LIVE')
        <span class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-500 text-white uppercase tracking-wider shadow-sm shadow-emerald-500/20">
            Livré
        </span>
    @elseif(strtoupper($colis->statut) === 'EN COURS')
        <span class="px-3 py-1 rounded-full text-[10px] font-black bg-orange-500 text-white uppercase tracking-wider shadow-sm shadow-orange-500/20">
            En cours
        </span>
    @elseif(strtoupper($colis->statut) === 'RETOURNÉ' || strtoupper($colis->statut) === 'RETOURNE')
        <span class="px-3 py-1 rounded-full text-[10px] font-black bg-rose-500 text-white uppercase tracking-wider shadow-sm shadow-rose-500/20">
            Retourné
        </span>
    @else
        <span class="px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-700 uppercase tracking-wider">
            {{ $colis->statut }}
        </span>
    @endif
</td>
                      
                                
                                <td class="py-5 px-6 text-center">
                                    <div class="flex justify-center items-center gap-3.5">
                                        <a href="{{ route('admin.colis.edit', $colis->id) }}" class="text-slate-400 hover:text-[#0A4BB3] transition text-base">
                                            <i class="fas fa-edit" title="Modifier"></i>
                                        </a>

                                        <form action="{{ route('admin.colis.destroy', $colis->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce colis ?');" class="inline m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition bg-transparent border-0 p-0 cursor-pointer align-middle text-base">
                                                <i class="fas fa-trash-alt" title="Supprimer"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-10 text-center text-slate-400 font-bold">
                                    <i class="fas fa-box-open text-2xl block mb-2 text-slate-300"></i> Aucun colis trouvé dans la base de données.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
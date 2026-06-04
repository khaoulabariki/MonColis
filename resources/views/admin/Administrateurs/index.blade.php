@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">

    <!-- Messages de Notification (Succès et Erreurs de validation) -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire d'ajout d'un nouvel Administrateur (Masqué par défaut) -->
    <div id="addAdminForm" class="hidden bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6 transition-all">
        <div class="flex justify-between items-center mb-4 border-b border-slate-50 pb-3">
            <h3 class="text-lg font-bold text-slate-800">
                <i class="fas fa-user-plus text-purple-500 mr-2"></i>Nouveau Administrateur
            </h3>
            <button onclick="toggleForm()" class="text-slate-400 hover:text-slate-600 bg-transparent border-0 cursor-pointer text-lg">&times;</button>
        </div>
        
        <form action="{{ route('admin.administrateurs.store') }}" method="POST" class="space-y-4 m-0 p-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Nom</label>
                    <input type="text" name="nom" required class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg p-2.5 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Prénom</label>
                    <input type="text" name="prenom" required class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg p-2.5 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Adresse Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg p-2.5 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Téléphone</label>
                    <input type="text" name="telephone" required class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg p-2.5 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Mot de passe</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg p-2.5 focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="toggleForm()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium text-xs px-4 py-2.5 rounded-lg transition cursor-pointer">Annuler</button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs px-4 py-2.5 rounded-lg transition shadow-sm cursor-pointer">Enregistrer</button>
            </div>
        </form>
    </div>
        
    <!-- Tableau d'affichage de la liste des Administrateurs -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- En-tête du Tableau avec titre et bouton d'action -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Liste des Administrateurs</h2>
                <p class="text-slate-400 text-xs mt-0.5">Gérez les accès et les profils administratifs de la plateforme.</p>
            </div>
            
            <!-- Bouton pour ouvrir / fermer le formulaire d'ajout -->
            <button onclick="toggleForm()" class="bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs px-4 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 cursor-pointer">
                <i class="fas fa-user-plus"></i> Ajouter un Admin
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Nom & Prénom</th>
                        <th class="p-4">Adresse Email</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($adminsList as $admin)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-medium text-slate-800">
                                <i class="fas fa-user-shield text-purple-500 mr-2"></i> {{ $admin->nom }} {{ $admin->prenom }}
                            </td>
                            <td class="p-4 font-mono text-xs text-slate-500">{{ $admin->email }}</td>
                            <td class="p-4">{{ $admin->telephone }}</td>
                            <td class="p-4 text-center">
                                <!-- Sécurité : Empêcher l'administrateur connecté de se supprimer lui-même -->
                                @if(auth()->id() !== $admin->id)
                                    <form action="{{ route('admin.administrateurs.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet administrateur ?');" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition bg-transparent border-0 p-0 cursor-pointer align-middle">
                                            <i class="fas fa-trash-alt" title="Supprimer"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">Vous (Connecté)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400">Aucun administrateur trouvé dans la base de données.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script JavaScript pour afficher/masquer le formulaire d'ajout -->
<script>
    function toggleForm() {
        const form = document.getElementById('addAdminForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection
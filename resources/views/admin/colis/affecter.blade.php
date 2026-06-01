<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Affecter un Colis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div style="display:flex; min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="width:256px; background-color:#1d4ed8; color:white; display:flex; flex-direction:column; min-height:100vh;">
        <div class="p-6 text-2xl font-bold border-b border-blue-600">MonColis</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Dashboard</a>
            <a href="{{ route('admin.colis.index') }}" class="block px-4 py-2 rounded-lg bg-blue-800">Colis</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Utilisateurs</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Affectations</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Finances</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Audit Log</a>
        </nav>
        <div class="p-4 border-t border-blue-600">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-600">Déconnexion</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 p-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.colis.index') }}" class="text-blue-600 hover:underline text-sm">← Retour</a>
            <h1 class="text-2xl font-bold text-gray-800">Affecter le Colis</h1>
        </div>

        {{-- Info colis --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 max-w-2xl">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations du Colis</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Code suivi</p>
                    <p class="font-mono font-bold text-blue-600">{{ $colis->code_suivi }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Destinataire</p>
                    <p class="font-medium">{{ $colis->prenom_destinataire }} {{ $colis->nom_destinataire }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Adresse</p>
                    <p class="font-medium">{{ $colis->adresse_destinataire }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Prix</p>
                    <p class="font-medium">{{ $colis->prix }} DH</p>
                </div>
            </div>
        </div>

        {{-- Formulaire affectation --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Choisir un Livreur</h2>

            @if($livreurs->isEmpty())
                <p class="text-gray-400 text-center py-4">Aucun livreur disponible.</p>
            @else
                <form method="POST" action="{{ route('admin.colis.store.affecter', $colis->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Livreur</label>
                        <select name="livreur_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Sélectionner un livreur --</option>
                            @foreach($livreurs as $livreur)
                                <option value="{{ $livreur->id }}">
                                    {{ $livreur->prenom }} {{ $livreur->nom }} — {{ $livreur->telephone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition">
                        Confirmer l'affectation
                    </button>
                </form>
            @endif
        </div>
    </main>
</div>
</body>
</html>
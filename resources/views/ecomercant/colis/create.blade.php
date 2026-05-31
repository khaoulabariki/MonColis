<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Nouveau Colis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div style="display:flex; min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="width:256px; background-color:#4f46e5; color:white; display:flex; flex-direction:column;">
        <div class="p-6 text-2xl font-bold border-b border-indigo-600">MonColis</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('ecomercant.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-indigo-600">Dashboard</a>
            <a href="{{ route('ecomercant.colis.index') }}" class="block px-4 py-2 rounded-lg hover:bg-indigo-600">Mes Colis</a>
            <a href="{{ route('ecomercant.colis.create') }}" class="block px-4 py-2 rounded-lg bg-indigo-800">Nouveau Colis</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-indigo-600">Mon Wallet</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-indigo-600">Retraits</a>
        </nav>
        <div class="p-4 border-t border-indigo-600">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-indigo-600">Déconnexion</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Enregistrer un Colis</h1>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
            <form method="POST" action="{{ route('ecomercant.colis.store') }}">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom destinataire</label>
                        <input type="text" name="nom_destinataire" value="{{ old('nom_destinataire') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom destinataire</label>
                        <input type="text" name="prenom_destinataire" value="{{ old('prenom_destinataire') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone destinataire</label>
                    <input type="text" name="telephone_destinataire" value="{{ old('telephone_destinataire') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse de livraison</label>
                    <textarea name="adresse_destinataire" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required>{{ old('adresse_destinataire') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poids (kg)</label>
                        <input type="number" name="poids" step="0.1" value="{{ old('poids') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix (DH)</label>
                        <input type="number" name="prix" step="0.01" value="{{ old('prix') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition duration-200">
                    Enregistrer le Colis
                </button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
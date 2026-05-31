<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Espace Livreur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" style="display:flex; min-height:100vh;">
    <div style="display:flex; min-height:100vh;">

        {{-- Sidebar --}}
        <aside class="w-64 bg-orange-600 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-orange-500">
                MonColis
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="block px-4 py-2 rounded-lg bg-orange-700">Dashboard</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-orange-500">Mes Livraisons</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-orange-500">En cours</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-orange-500">Historique</a>
            </nav>
            <div class="p-4 border-t border-orange-500">
                <p class="text-sm text-orange-200 mb-2">
                    {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                </p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-orange-500">
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Bonjour, {{ Auth::user()->prenom }} 👋
            </h1>
            <p class="text-gray-500 mb-8">Vos livraisons du jour</p>

            {{-- KPIs --}}
            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">Colis Affectés</p>
                    <p class="text-3xl font-bold text-orange-600">0</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">Livrés Aujourd'hui</p>
                    <p class="text-3xl font-bold text-green-600">0</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">En cours</p>
                    <p class="text-3xl font-bold text-blue-600">0</p>
                </div>
            </div>

            {{-- Liste colis affectés --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Colis à livrer
                </h2>
                <div class="text-center text-gray-400 py-8">
                    Aucun colis affecté pour le moment.
                </div>
            </div>
        </main>

    </div>

</body>
</html>
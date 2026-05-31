<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Dashboard Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-blue-600">
                MonColis
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="block px-4 py-2 rounded-lg bg-blue-800">Dashboard</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Colis</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Livreurs</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">E-commerçants</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Affectations</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Finances</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-600">Audit Log</a>
            </nav>
            <div class="p-4 border-t border-blue-600">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-600">
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tableau de bord</h1>

            {{-- KPIs --}}
            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">Total Colis</p>
                    <p class="text-3xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">Livrés</p>
                    <p class="text-3xl font-bold text-green-600">0</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">En cours</p>
                    <p class="text-3xl font-bold text-orange-500">0</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-500">Retournés</p>
                    <p class="text-3xl font-bold text-red-500">0</p>
                </div>
            </div>

            <p class="text-gray-400 text-center mt-20">Dashboard en cours de développement...</p>
        </main>

    </div>

</body>
</html>
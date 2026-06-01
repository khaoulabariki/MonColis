<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Colis - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">
        <div class="w-64 bg-blue-900 text-white flex flex-col">
            <div class="p-5 text-xl font-bold border-b border-blue-800 tracking-wider">
                MonColis
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" onclick="window.location.href='{{ route('admin.dashboard') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">Dashboard</a>
                <a href="{{ route('admin.colis.index') }}" onclick="window.location.href='{{ route('admin.colis.index') }}'; return true;" class="block px-4 py-2.5 rounded-lg bg-blue-950 text-white font-medium">Colis</a>
                <a href="{{ route('admin.users.index') }}" onclick="window.location.href='{{ route('admin.users.index') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">Utilisateurs</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">Livreurs</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">E-commerçants</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">Affectations</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">Finances</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-blue-100 hover:bg-blue-800 transition">Audit Log</a>
            </nav>
            <div class="p-4 border-t border-blue-800 text-xs text-blue-300">
                © 2026 MonColis App
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <div class="text-sm text-gray-500">Espace Administration</div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Administrateur</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:underline cursor-pointer">Déconnexion</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
                
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Gestion des Colis</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm cursor-pointer">
                        + Créer un nouveau colis
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Code Barre</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Destinataire</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Ville</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Prix (DH)</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Statut</th>
                                <th class="text-center px-6 py-3 text-gray-600 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Données statiques de test pro --}}
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono font-medium text-blue-600">MC-84729</td>
                                <td class="px-6 py-4 text-gray-800">Youssef El Alami</td>
                                <td class="px-6 py-4 text-gray-500">Casablanca</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">180 DH</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">En cours</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-2 cursor-pointer">Modifier</button>
                                    <button class="text-red-600 hover:text-red-800 text-xs font-medium cursor-pointer">Supprimer</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono font-medium text-blue-600">MC-91024</td>
                                <td class="px-6 py-4 text-gray-800">Halima Bensouda</td>
                                <td class="px-6 py-4 text-gray-500">Rabat</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">350 DH</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Livré</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-2 cursor-pointer">Modifier</button>
                                    <button class="text-red-600 hover:text-red-800 text-xs font-medium cursor-pointer">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

</body>
</html>
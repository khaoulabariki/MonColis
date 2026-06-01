<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Affectations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        {{-- Sidebar identical --}}
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-blue-600">MonColis</div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">Dashboard</a>
                <a href="{{ route('admin.colis.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">Colis</a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">Utilisateurs</a>
                <a href="{{ route('admin.livreurs.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">Livreurs</a>
                <a href="{{ route('admin.ecomercants.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">E-commerçants</a>
                <a href="{{ route('admin.affectations.index') }}" class="block px-4 py-2 rounded-lg bg-blue-800">Affectations</a>
                <a href="{{ route('admin.finances.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">Finances</a>
                <a href="{{ route('admin.audit.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition">Audit Log</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Affectations</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <p class="text-gray-500 text-center py-4">Liste des affectations en cours de développement...</p>
            </div>
        </main>
    </div>
</body>
</html>
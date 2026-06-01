<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">
        <div class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="p-5 text-xl font-bold border-b border-slate-800 tracking-wider">
                MonColis Admin
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" onclick="window.location.href='{{ route('admin.dashboard') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Dashboard</a>
                <a href="{{ route('admin.colis.index') }}" onclick="window.location.href='{{ route('admin.colis.index') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Colis</a>
                <a href="{{ route('admin.users.index') }}" onclick="window.location.href='{{ route('admin.users.index') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Utilisateurs</a>
                <a href="{{ route('admin.livreurs.index') }}" onclick="window.location.href='{{ route('admin.livreurs.index') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Livreurs</a>
                <a href="{{ route('admin.ecomercants.index') }}" onclick="window.location.href='{{ route('admin.ecomercants.index') }}'; return true;" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">E-commerçants</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Affectations</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Finances</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">Audit Log</a>
            </nav>
            <div class="p-4 border-t border-slate-800 text-xs text-slate-500">
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
                    <h1 class="text-2xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm inline-block">
                        + Ajouter un utilisateur
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Nom / Prénom</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Email</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Téléphone</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Rôle</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-medium">Statut</th>
                                <th class="text-center px-6 py-3 text-gray-600 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $user->prenom }} {{ $user->nom }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->telephone ?? '-' }}</td>
                                
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700">Admin</span>
                                    @elseif($user->role === 'livreur')
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">Livreur</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">E-commerçant</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ (int)$user->statut === 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ (int)$user->statut === 1 ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if((int)$user->statut === 1)
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-md text-xs font-medium transition border border-red-200 cursor-pointer">
                                                Désactiver
                                            </button>
                                        @else
                                            <button type="submit" class="bg-green-50 hover:bg-green-100 text-green-600 px-3 py-1.5 rounded-md text-xs font-medium transition border border-green-200 cursor-pointer">
                                                Activer
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    Aucun utilisateur trouvé dans la base de données.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

</body>
</html>
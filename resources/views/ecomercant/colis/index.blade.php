<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Mes Colis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div style="display:flex; min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="width:256px; background-color:#4f46e5; color:white; display:flex; flex-direction:column;">
        <div class="p-6 text-2xl font-bold border-b border-indigo-600">MonColis</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('ecomercant.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-indigo-600">Dashboard</a>
            <a href="{{ route('ecomercant.colis.index') }}" class="block px-4 py-2 rounded-lg bg-indigo-800">Mes Colis</a>
            <a href="{{ route('ecomercant.colis.create') }}" class="block px-4 py-2 rounded-lg hover:bg-indigo-600">Nouveau Colis</a>
           <a href="/ecomercant/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"> Mon Wallet</a>
           <a href="/ecomercant/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"> Retraits</a>
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Mes Colis</h1>
            <a href="{{ route('ecomercant.colis.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + Nouveau Colis
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Code Suivi</th>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Destinataire</th>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Adresse</th>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Prix</th>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Statut</th>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($colis as $c)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-indigo-600">{{ $c->code_suivi }}</td>
                        <td class="px-6 py-4">{{ $c->prenom_destinataire }} {{ $c->nom_destinataire }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $c->adresse_destinataire }}</td>
                        <td class="px-6 py-4 font-medium">{{ $c->prix }} DH</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'enregistre' => 'bg-gray-100 text-gray-700',
                                    'ramasse'    => 'bg-blue-100 text-blue-700',
                                    'en_cours'   => 'bg-orange-100 text-orange-700',
                                    'livre'      => 'bg-green-100 text-green-700',
                                    'retourne'   => 'bg-red-100 text-red-700',
                                    'annule'     => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$c->statut] }}">
                                {{ ucfirst(str_replace('_', ' ', $c->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('tracking', $c->token_suivi) }}"
                                class="text-indigo-600 hover:underline text-xs">
                                Suivi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Aucun colis enregistré pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $colis->links() }}
            </div>
        </div>
    </main>
</div>
</body>
</html>
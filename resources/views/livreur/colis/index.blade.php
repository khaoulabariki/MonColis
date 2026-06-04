<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Mes Livraisons</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div style="display:flex; min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="width:256px; background-color:#ea580c; color:white; display:flex; flex-direction:column; min-height:100vh;">
        <div class="p-6 text-2xl font-bold border-b border-orange-500">MonColis</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('livreur.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-orange-500">Dashboard</a>
            <a href="{{ route('livreur.mes_livraisons') }}" class="block px-4 py-2 rounded-lg bg-orange-700">Mes Livraisons</a>
        </nav>
        <div class="p-4 border-t border-orange-500">
            <p class="text-sm text-orange-200 mb-2">
                {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
            </p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-orange-500">Déconnexion</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Mes Livraisons</h1>

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
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Statut</th>
                        <th class="text-left px-6 py-3 text-gray-600 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($affectations as $affectation)
                    @php $c = $affectation->colis; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-orange-600 font-medium">{{ $c->code_suivi }}</td>
                        <td class="px-6 py-4">{{ $c->prenom_destinataire }} {{ $c->nom_destinataire }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $c->adresse_destinataire }}</td>
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
                            @if($c->statut !== 'livre' && $c->statut !== 'retourne')
                            <form method="POST" action="{{ route('livreur.colis.statut', $c->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="flex gap-2">
                                    <select name="statut" class="border border-gray-300 rounded px-2 py-1 text-xs">
                                        <option value="en_cours">En cours</option>
                                        <option value="livre">Livré</option>
                                        <option value="retourne">Retourné</option>
                                    </select>
                                    <button type="submit"
                                        class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-xs">
                                        Mettre à jour
                                    </button>
                                </div>
                            </form>
                            @else
                                <span class="text-gray-400 text-xs">Terminé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            Aucune livraison affectée pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
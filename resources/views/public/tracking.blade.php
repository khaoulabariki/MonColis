<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Suivi de Colis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <header style="background-color:#4f46e5;" class="text-white py-4 px-8 shadow">
        <h1 class="text-2xl font-bold">MonColis</h1>
        <p class="text-indigo-200 text-sm">Suivi de votre livraison</p>
    </header>

    <main class="max-w-2xl mx-auto py-10 px-4">

        {{-- Info colis --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informations du Colis</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Code de suivi</p>
                    <p class="font-mono font-bold text-indigo-600">{{ $colis->code_suivi }}</p>
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
                    <p class="text-gray-500">Statut actuel</p>
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
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$colis->statut] }}">
                        {{ ucfirst(str_replace('_', ' ', $colis->statut)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Timeline de livraison</h2>
            @php
                $etapes = [
                    'enregistre' => ['label' => 'Colis enregistré',     'icon' => '📦'],
                    'ramasse'    => ['label' => 'Ramassé par l\'agence', 'icon' => '🏪'],
                    'en_cours'   => ['label' => 'En cours de livraison','icon' => '🚚'],
                    'livre'      => ['label' => 'Livré',                 'icon' => '✅'],
                    'retourne'   => ['label' => 'Retourné',              'icon' => '↩️'],
                ];
                $ordre = ['enregistre','ramasse','en_cours','livre'];
                $statutActuel = $colis->statut;
                $indexActuel = array_search($statutActuel, $ordre);
            @endphp

            <div class="space-y-4">
                @foreach($ordre as $i => $etape)
                @php $actif = $i <= $indexActuel; @endphp
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                        {{ $actif ? 'bg-indigo-100' : 'bg-gray-100' }}">
                        {{ $etapes[$etape]['icon'] }}
                    </div>
                    <div class="flex-1">
                        <p class="font-medium {{ $actif ? 'text-gray-800' : 'text-gray-400' }}">
                            {{ $etapes[$etape]['label'] }}
                        </p>
                    </div>
                    @if($actif)
                        <span class="text-indigo-600 text-sm font-medium">✓</span>
                    @endif
                </div>
                @if(!$loop->last)
                <div class="ml-5 w-0.5 h-4 {{ $i < $indexActuel ? 'bg-indigo-300' : 'bg-gray-200' }}"></div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Feedback --}}
        @if($colis->statut === 'livre' && !$colis->avis)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Évaluer la livraison</h2>
            <form method="POST" action="{{ route('avis.store', $colis->token_suivi) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="note" value="{{ $i }}" class="hidden">
                            <span class="text-2xl hover:scale-110 transition">⭐</span>
                        </label>
                        @endfor
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                    <textarea name="commentaire" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Votre avis sur la livraison..."></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg">
                    Envoyer mon avis
                </button>
            </form>
        </div>
        @elseif($colis->avis)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <p class="text-green-700 font-medium">✅ Merci pour votre avis !</p>
        </div>
        @endif

    </main>

</body>
</html>
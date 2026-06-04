<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        {{-- Logo de l'application --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">MonColis</h1>
            <p class="text-gray-500 text-sm mt-1">Plateforme de gestion des livraisons</p>
        </div>

        {{-- Affichage des erreurs de validation --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Formulaire de connexion sécurisé --}}
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Champ : Adresse Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Adresse email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="exemple@email.com"
                >
            </div>

            {{-- Champ : Mot de passe (Correction du name pour correspondre au Controller) --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe
                </label>
                <input
                    type="password"
                    name="password" {{-- Modifié ici : 'password' au lieu de 'mot_de_passe' --}}
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="••••••••"
                >
            </div>

            {{-- Bouton de soumission --}}
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition duration-200"
            >
                Se connecter
            </button>
        </form>
    </div>

</body>
</html>
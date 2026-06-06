<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis - Inscription Espace Pro</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans antialiased text-gray-800 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-50 rounded-2xl text-indigo-600 mb-3 text-2xl">
                <i class="fas fa-box-open"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Rejoignez MonColis</h2>
            <p class="text-sm text-gray-500 mt-1">Créez votre compte professionnel en quelques secondes</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
                <ul class="list-disc list-inside m-0">
                    @foreach ($errors->all() as $error)`
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="role" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Vous êtes ?</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-user-tag text-sm"></i>
                    </span>
                    <select name="role" id="role" required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 bg-white text-gray-700 font-medium text-sm transition">
                        <option value="" disabled selected>Choisir votre profil...</option>
                        <option value="ecommercant">🛍️ E-commerçant (Vendeur)</option>
                        <option value="livreur">🚚 Livreur (Livreur indépendant)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nom" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nom</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required placeholder="Ex: Alaoui"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 text-sm transition">
                </div>
                <div>
                    <label for="prenom" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required placeholder="Ex: Ahmed"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 text-sm transition">
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Adresse Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="exemple@domaine.com"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 text-sm transition">
            </div>

            <div>
                <label for="telephone" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">N° Téléphone (WhatsApp)</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" placeholder="Ex: 2126XXXXXXXX"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 text-sm transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 text-sm transition">
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-100 cursor-pointer">
                    Créer mon compte professionnel
                </button>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500">
                Vous avez déjà un compte ? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline ml-1">Connectez-vous</a>
            </p>
        </div>

    </div>

</body>
</html>
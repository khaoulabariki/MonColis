<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f9fa] text-gray-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <div class="w-64 bg-[#d9531e] flex flex-col justify-between text-white shadow-md">
            <div class="p-6">
                <div class="text-2xl font-bold tracking-wide mb-10 px-2">
                    MonColis 
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('livreur.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium bg-white/10 text-white transition-all">
                        Dashboard
                    </a>

                    <a href="{{ route('livreur.mes_livraisons') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/80 hover:bg-white/5 hover:text-white transition-all">
                        Mes Livraisons
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-white/10 bg-black/10 flex flex-col gap-2">
                <div class="text-xs text-white/70 px-2 truncate">
                    👤 Livreur Connecté
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 px-2 py-1.5 text-xs font-medium text-white/80 hover:text-white rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl">
                
                <h1 class="text-2xl font-semibold text-gray-700 tracking-tight mb-6">Tableau de bord</h1>
                
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-4">Statistiques des colis</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 bg-orange-50 rounded-xl border border-orange-100">
                            <span class="text-xs font-semibold text-orange-700 uppercase tracking-wider block mb-1">Mes Colis Assignés</span>
                            <p class="text-3xl font-extrabold text-gray-900">
                                {{ $colisCount ?? 0 }} <span class="text-sm font-normal text-gray-500 ml-1">colis prêt(s) à livrer</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>

</body>
</html>
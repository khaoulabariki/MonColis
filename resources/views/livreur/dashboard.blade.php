<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis — Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] text-gray-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-[#d9531e] flex flex-col justify-between text-white shadow-md flex-shrink-0">
            <div class="p-6">
                <div class="text-2xl font-bold tracking-wide mb-10 px-2">
                    MonColis 
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('livreur.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium bg-white/10 text-white transition-all">
                        <i class="fas fa-home w-5 text-center"></i> Dashboard
                    </a>

                    <a href="{{ route('livreur.mes_livraisons') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/80 hover:bg-white/5 hover:text-white transition-all">
                        <i class="fas fa-truck w-5 text-center"></i> Mes Livraisons
                    </a>
                </nav>
            </div>

            <!-- User Session / Logout -->
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

        <!-- Main Content (Expanded to full width using w-full) -->
        <main class="flex-1 overflow-y-auto p-8 w-full">
            <div class="w-full">
                
                <h1 class="text-2xl font-semibold text-gray-700 tracking-tight mb-6">Tableau de bord</h1>
                
                <!-- Statistics Section -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-4">Statistiques des colis</p>
                    
                    <!-- Grid Layout for the 3 Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Card 1: Assigned Colis -->
                        <div class="p-5 bg-orange-50 rounded-xl border border-orange-100 flex flex-col justify-between min-h-[140px]">
                            <div>
                                <span class="text-xs font-semibold text-orange-700 uppercase tracking-wider block mb-1">Mes Colis Assignés</span>
                                <p class="text-3xl font-extrabold text-gray-900 mt-2">
                                    {{ $colisCount ?? 0 }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-500 font-normal mt-2 block">colis prêt(s) à livrer</span>
                        </div>

                        <!-- Card 2: Wallet / Cash in Hand -->
                        <div class="p-5 bg-emerald-50 rounded-xl border border-emerald-100 flex flex-col justify-between min-h-[140px]">
                            <div>
                                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider block mb-1">Mon Portefeuille (Cash en main)</span>
                                <p class="text-3xl font-extrabold text-gray-900 mt-2">
                                    {{ number_format(\App\Models\Colis::where('livreur_id', auth()->id())->where('statut', 'livre')->sum('prix'), 2) }} <span class="text-lg font-bold text-gray-500">DH</span>
                                </p>
                            </div>
                            <span class="text-[11px] text-emerald-600 font-medium mt-2 block"><i class="fas fa-info-circle mr-1"></i> Total du cash collecté à verser à l'administration</span>
                        </div>

                        <!-- Card 3: Mes Gains Réels -->
                        <div class="p-5 bg-indigo-50 rounded-xl border border-indigo-100 flex flex-col justify-between min-h-[140px]">
                            <div>
                                <span class="text-xs font-semibold text-indigo-700 uppercase tracking-wider block mb-1">Mes Gains (Commissions)</span>
                                <p class="text-3xl font-extrabold text-gray-900 mt-2">
                                    {{ number_format(\App\Models\Colis::where('livreur_id', auth()->id())->where('statut', 'livre')->count() * 20, 2) }} <span class="text-lg font-bold text-gray-500">DH</span>
                                </p>
                            </div>
                            <span class="text-[11px] text-indigo-600 font-medium mt-2 block"><i class="fas fa-coins mr-1"></i> Votre gain net (20 DH par colis livré)</span>
                        </div>

                    </div>
                </div>

                <!-- New Section: Large Table for Delivered Packages History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-lg"><i class="fas fa-history text-gray-400 mr-2"></i> Historique des colis livrés</h3>
                        <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full">Encaissé</span>
                    </div>
                    
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[11px] tracking-wider border-b border-gray-100">
                                <tr>
                                    <th class="p-4">Code Colis</th>
                                    <th class="p-4">Destinataire</th>
                                    <th class="p-4">Date de Livraison</th>
                                    <th class="p-4">Montant Collecté</th>
                                    <th class="p-4">Ma Commission</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse(\App\Models\Colis::where('livreur_id', auth()->id())->where('statut', 'livre')->orderBy('updated_at', 'desc')->get() as $colisLivre)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="p-4 font-semibold text-indigo-600">{{ $colisLivre->code_suivi }}</td>
                                        <td class="p-4 text-gray-700 font-medium">{{ $colisLivre->nom_destinataire }} {{ $colisLivre->prenom_destinataire }}</td>
                                        <td class="p-4 text-xs text-gray-400">{{ $colisLivre->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-4 font-bold text-gray-900">{{ number_format($colisLivre->prix, 2) }} DH</td>
                                        <td class="p-4 text-emerald-600 font-bold">+ 20.00 DH</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-400 text-xs">Aucun colis marqué comme "Livré" pour le moment.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

    </div>

</body>
</html>
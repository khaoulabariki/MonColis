<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily — Espace Livreur</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-brand-blue { color: #0A4BB3; }
        .bg-brand-blue { background-color: #0A4BB3; }
        .hover\:bg-brand-blue-dark:hover { background-color: #083D93; }
        .text-brand-orange { color: #FF6B00; }
        .bg-brand-orange { background-color: #FF6B00; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <div class="w-64 bg-white flex flex-col justify-between text-slate-700 border-r border-slate-100 flex-shrink-0 shadow-xs">
            <div class="p-6">
                <div class="flex items-center gap-2.5 mb-10 px-2">
                    <div class="w-8 h-8 bg-brand-blue rounded-lg flex items-center justify-center relative overflow-hidden shadow-xs">
                        <div class="absolute inset-0 border-r-2 border-white/20 transform rotate-45 scale-150"></div>
                        <i class="fas fa-arrow-up text-brand-orange text-xs transform rotate-45"></i>
                    </div>
                    <span class="text-xl font-black tracking-tight"><span class="text-brand-blue">Ship</span><span class="text-brand-orange">ily</span></span>
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('livreur.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-black bg-blue-50 text-brand-blue transition-all">
                        <i class="fas fa-home w-5 text-center text-sm"></i> Dashboard
                    </a>

                    <a href="{{ route('livreur.mes_livraisons') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-truck w-5 text-center text-sm"></i> Mes Livraisons
                    </a>

                    <a href="{{ route('profil.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-user-cog w-5 text-center text-sm"></i> Mon Profil
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col gap-2">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider px-2 truncate flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Livreur En Ligne
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 px-2 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                        <i class="fas fa-sign-out-alt w-4"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto p-8 w-full">
            <div class="w-full max-w-7xl mx-auto">
                
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tableau de bord</h1>
                    <p class="text-xs font-medium text-slate-400 mt-1">Aperçu en temps réel de votre activity de livraison.</p>
                </div>
                
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs mb-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Suivi du portefeuille & colis</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/50 flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                            <div>
                                <span class="text-[10px] font-black text-brand-orange uppercase tracking-wider bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100/50 inline-block mb-1">Colis Assignés</span>
                                <p class="text-3xl font-black text-slate-900 mt-2">
                                    {{ $colisCount ?? 0 }}
                                </p>
                            </div>
                            <span class="text-xs text-slate-400 font-medium mt-3 block"><i class="fas fa-box mr-1"></i> Prêt(s) à être livrés</span>
                        </div>

                        @php
                            // 🎯 الحساب الصحيح والمصفى لي كياخد غي الكوليس لي مزال ماتكلوتراوش
                            $totalCashEnMain = \App\Models\Colis::where('livreur_id', auth()->id())
                                ->whereIn('statut', ['livre', 'Livré', 'livré', 'Livre'])
                                ->where('encaissement_admin', false)
                                ->sum('prix');

                            $totalCommissions = \App\Models\Colis::where('livreur_id', auth()->id())
                                ->whereIn('statut', ['livre', 'Livré', 'livré', 'Livre'])
                                ->where('encaissement_admin', false)
                                ->count() * 20;

                            $resteADonnerAdmin = $totalCashEnMain - $totalCommissions;
                        @endphp

                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/50 flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                            <div>
                                <span class="text-[10px] font-black text-slate-600 uppercase tracking-wider bg-slate-200/60 px-2 py-0.5 rounded-md inline-block mb-1">Cash Total En Main</span>
                                <p class="text-3xl font-black text-slate-900 mt-2">
                                    {{ number_format($totalCashEnMain, 2) }} <span class="text-sm font-bold text-slate-400">DH</span>
                                </p>
                            </div>
                            <span class="text-[11px] text-slate-500 font-bold mt-3 block"><i class="fas fa-wallet mr-1"></i> Total collecté sur le terrain</span>
                        </div>

                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/50 flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                            <div>
                                <span class="text-[10px] font-black text-brand-blue uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100/50 inline-block mb-1">Mes Commissions</span>
                                <p class="text-3xl font-black text-brand-blue mt-2">
                                    {{ number_format($totalCommissions, 2) }} <span class="text-sm font-bold text-blue-300">DH</span>
                                </p>
                            </div>
                            <span class="text-[11px] text-brand-blue font-bold mt-3 block"><i class="fas fa-coins mr-1"></i> Votre gain net (20 DH / colis)</span>
                        </div>

                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/50 flex flex-col justify-between min-h-[140px] hover:shadow-md border-l-4 border-l-rose-500 transition duration-200">
                            <div>
                                <span class="text-[10px] font-black text-rose-600 uppercase tracking-wider bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100/50 inline-block mb-1">À Verser à l'Admin</span>
                                <p class="text-3xl font-black text-rose-600 mt-2">
                                    {{ number_format($resteADonnerAdmin, 2) }} <span class="text-sm font-bold text-rose-300">DH</span>
                                </p>
                            </div>
                            <span class="text-[11px] text-rose-500 font-bold mt-3 block"><i class="fas fa-arrow-circle-right mr-1"></i> Montant dû à l'administration</span>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-black text-slate-900 text-base flex items-center gap-2"><i class="fas fa-history text-slate-400"></i> Historique des colis livrés</h3>
                        <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-100 font-black uppercase tracking-wider px-3 py-1 rounded-xl shadow-xs">Encaissé</span>
                    </div>
                    
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse text-sm text-slate-600">
                            <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="p-4 pl-6">Code Colis</th>
                                    <th class="p-4">Destinataire</th>
                                    <th class="p-4">Date de Livraison</th>
                                    <th class="p-4">Montant Collecté</th>
                                    <th class="p-4 pr-6">Ma Commission</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-bold">
                                @forelse(\App\Models\Colis::where('livreur_id', auth()->id())->whereIn('statut', ['livre', 'Livré', 'cloture'])->orderBy('updated_at', 'desc')->get() as $colisLivre)
                                    <tr class="hover:bg-slate-50/40 transition">
                                        <td class="p-4 pl-6 font-black text-brand-blue">{{ $colisLivre->code_suivi }}</td>
                                        <td class="p-4 text-slate-700">{{ $colisLivre->nom_destinataire }} {{ $colisLivre->prenom_destinataire }}</td>
                                        <td class="p-4 font-medium text-slate-400">{{ $colisLivre->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-4 font-black text-slate-900">{{ number_format($colisLivre->prix, 2) }} DH</td>
                                        <td class="p-4 pr-6 text-emerald-600 font-black">+ 20.00 DH</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400 text-xs font-medium">Aucun colis marqué comme "Livré" pour le moment.</td>
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
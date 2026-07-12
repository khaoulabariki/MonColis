<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily — Mes Colis</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-brand-blue { color: #0A4BB3; }
        .bg-brand-blue { background-color: #0A4BB3; }
        .hover\:bg-brand-blue-dark:hover { background-color: #083D93; }
        .text-brand-orange { color: #FF6B00; }
        .bg-brand-orange { background-color: #FF6B00; }
        .hover\:bg-brand-orange-dark:hover { background-color: #E05E00; }
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
                    <a href="{{ route('ecommercant.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-home w-5 text-center text-sm"></i> Mon Dashboard
                    </a>
                    <a href="{{ route('ecommercant.colis.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-black bg-blue-50 text-brand-blue transition-all">
                        <i class="fas fa-box w-5 text-center text-sm"></i> Mes Colis
                    </a>
                    <a href="{{ route('ecommercant.colis.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-plus-circle w-5 text-center text-sm"></i> Nouveau Colis
                    </a>
                    <a href="/ecommercant/finances" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-wallet w-5 text-center text-sm"></i> Mon Wallet
                    </a>
                    <a href="/ecommercant/finances" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-hand-holding-usd w-5 text-center text-sm"></i> Demande Retrait
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col gap-2">
                <form method="POST" action="{{ route('logout') }}">
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
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mes Colis</h1>
                        <p class="text-xs font-medium text-slate-400 mt-1">Consultez et suivez l'état de l'ensemble de vos colis expédiés.</p>
                    </div>
                    <a href="{{ route('ecommercant.colis.create') }}"
                        class="bg-brand-orange hover:bg-brand-orange-dark text-white font-black px-4 py-2.5 rounded-xl text-xs tracking-wider uppercase transition shadow-xs flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-plus text-[10px]"></i> Nouveau Colis
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4 mb-6 flex items-center gap-3 text-xs font-bold shadow-xs">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse text-sm text-slate-600">
                            <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="p-4 pl-6">Code Suivi</th>
                                    <th class="p-4">Destinataire</th>
                                    <th class="p-4">Adresse</th>
                                    <th class="p-4">Prix</th>
                                    <th class="p-4 pr-6">Statut</th> </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-bold">
                                @forelse($colis as $c)
                                <tr class="hover:bg-slate-50/40 transition">
                                    <td class="p-4 pl-6 font-black text-brand-blue font-mono">{{ $c->code_suivi }}</td>
                                    <td class="p-4 text-slate-900">{{ $c->prenom_destinataire }} {{ $c->nom_destinataire }}</td>
                                    <td class="p-4 text-slate-500 font-medium max-w-xs truncate">{{ $c->adresse_destinataire }}</td>
                                    <td class="p-4 font-black text-slate-900">{{ number_format($c->prix, 2) }} DH</td>
                                    <td class="p-4 pr-6"> @php
                                            $colors = [
                                                'enregistre' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'ramasse'    => 'bg-blue-50 text-brand-blue border-blue-100',
                                                'en_cours'   => 'bg-orange-50 text-brand-orange border-orange-100',
                                                'livre'      => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'retourne'   => 'bg-rose-50 text-rose-700 border-rose-100',
                                                'annule'     => 'bg-rose-50 text-rose-700 border-rose-100',
                                            ];

                                            $labels = [
                                                'enregistre' => 'enregistré',
                                                'ramasse'    => 'ramassé',
                                                'en_cours'   => 'en cours',
                                                'livre'      => 'livré',
                                                'retourne'   => 'retourné',
                                                'annule'     => 'annulé',
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-wide {{ $colors[$c->statut] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $labels[$c->statut] ?? str_replace('_', ' ', $c->statut) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-400 font-medium">
                                        <div class="text-slate-300 text-2xl mb-2"><i class="fas fa-box-open"></i></div>
                                        Aucun colis enregistré pour le moment.
                                    </td>
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
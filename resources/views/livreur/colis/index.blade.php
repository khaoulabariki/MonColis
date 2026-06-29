<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily — Mes Livraisons</title>
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
                    <a href="{{ route('livreur.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-home w-5 text-center text-sm"></i> Dashboard
                    </a>

                    <a href="{{ route('livreur.mes_livraisons') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-black bg-blue-50 text-brand-blue transition-all">
                        <i class="fas fa-truck w-5 text-center text-sm"></i> Mes Livraisons
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col gap-2">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider px-2 truncate flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Livreur En Ligne
                </div>
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
                
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mes Livraisons</h1>
                    <p class="text-xs font-medium text-slate-400 mt-1">Gérez et mettez à jour l'état de vos colis assignés en temps réel.</p>
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
                                    <th class="p-4">Prix</th> {{-- 🆕 زدنا هاد العمود ف العنوان --}}
                                    <th class="p-4">Statut</th>
                                    <th class="p-4 pr-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-bold">
                                @forelse($affectations as $affectation)
                                @php $c = $affectation->colis; @endphp
                                <tr class="hover:bg-slate-50/40 transition">
                                    <td class="p-4 pl-6 font-black text-brand-blue font-mono">{{ $c->code_suivi }}</td>
                                    <td class="p-4 text-slate-900">{{ $c->prenom_destinataire }} {{ $c->nom_destinataire }}</td>
                                    <td class="p-4 text-slate-500 font-medium max-w-xs truncate">{{ $c->adresse_destinataire }}</td>
                                    <td class="p-4 text-slate-900 font-mono font-black text-emerald-600">{{ number_format($c->prix, 2) }} DH</td> {{-- 🆕 زدنا الثمن هنا ب صيغة أنيقة --}}
                                    <td class="p-4">
                                        @php
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
                                            
                                            $currentStatut = $c->statut;
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-wide {{ $colors[$currentStatut] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $labels[$currentStatut] ?? str_replace('_', ' ', $currentStatut) }}
                                        </span>
                                    </td>
                                    <td class="p-4 pr-6">
                                        @if($c->statut !== 'livre' && $c->statut !== 'retourne')
                                        <form method="POST" action="{{ route('livreur.colis.statut', $c->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center gap-2">
                                                <select name="statut" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition">
                                                    <option value="en_cours" {{ $c->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                                    <option value="livre" {{ $c->statut == 'livre' ? 'selected' : '' }}>Livré</option>
                                                    <option value="retourne" {{ $c->statut == 'retourne' ? 'selected' : '' }}>Retourné</option>
                                                </select>
                                                <button type="submit" class="bg-brand-blue hover:bg-brand-blue-dark text-white font-black px-3 py-1.5 rounded-xl transition shadow-xs text-xs whitespace-nowrap cursor-pointer">
                                                    <i class="fas fa-sync-alt mr-1"></i> Maj
                                                </button>
                                            </div>
                                        </form>
                                        @else
                                            <span class="text-slate-400 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wide inline-flex items-center gap-1">
                                                <i class="fas fa-check text-[9px]"></i> Terminé
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-slate-400 font-medium"> {{-- رجعناها 6 بلاصة 5 حيت تزاد عمود --}}
                                        <div class="text-slate-300 text-2xl mb-2"><i class="fas fa-box-open"></i></div>
                                        Aucune livraison affectée pour le moment.
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
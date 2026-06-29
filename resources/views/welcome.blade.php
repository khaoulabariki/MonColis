<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily - La Livraison E-commerce Intelligente</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .text-brand-blue { color: #0A4BB3; }
        .bg-brand-blue { background-color: #0A4BB3; }
        .hover\:bg-brand-blue-dark:hover { background-color: #083D93; }
        .text-brand-orange { color: #FF6B00; }
        .bg-brand-orange { background-color: #FF6B00; }
        .hover\:bg-brand-orange-dark:hover { background-color: #E05E00; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <nav class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-white/90">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-brand-blue rounded-xl flex items-center justify-center relative overflow-hidden shadow-md shadow-blue-900/10">
                    <div class="absolute inset-0 border-r-4 border-white/20 transform rotate-45 scale-150"></div>
                    <i class="fas fa-arrow-up text-brand-orange text-xl transform rotate-45 translate-x-0.5 -translate-y-0.5 drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]"></i>
                </div>
                
                <div class="flex flex-col">
                    <span class="text-2xl font-black tracking-tight leading-none">
                        <span class="text-brand-blue">Ship</span><span class="text-brand-orange">ily</span>
                    </span>
                    <span class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-1">LIVRAISON SIMPLIFIÉE</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#suivi" class="hover:text-brand-blue transition">Suivre un Colis</a>
                <a href="#services" class="hover:text-brand-blue transition">Nos Services</a>
                <a href="#workflow" class="hover:text-brand-blue transition">Notre Processus</a>
            </div>
            
            <div class="flex items-center gap-2.5">
                <a href="/login" class="bg-brand-blue text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-brand-blue-dark transition shadow-md shadow-blue-100 text-center whitespace-nowrap">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="bg-brand-blue text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-brand-blue-dark transition shadow-md shadow-blue-100 text-center whitespace-nowrap">
                    Inscription
                </a>
                <a href="{{ route('contact') }}" class="bg-brand-blue text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-brand-blue-dark transition shadow-md shadow-blue-100 text-center whitespace-nowrap">
                    Contactez-nous
                </a>
            </div>
        </div>
    </nav>

    <header id="suivi" class="py-20 md:py-24 bg-gradient-to-b from-white to-slate-50 border-b border-slate-100 relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter uppercase select-none">
                <span class="text-brand-blue">SHIP</span><span class="text-brand-orange">ILY</span>
            </h1>
            
            <h2 class="text-xl md:text-3xl font-extrabold text-brand-orange mt-4 tracking-wide">
                Un processus 100% digital
            </h2>
            
            <p class="text-slate-400 font-medium text-lg md:text-xl max-w-2xl mx-auto mt-6 leading-relaxed">
                La livraison e-commerce pensée par et pour les e-commerçants !
            </p>

            <div class="mt-12 max-w-2xl mx-auto">
                <form action="/tracking" method="GET" class="bg-white p-3 rounded-2xl shadow-2xl border border-slate-100/80 flex flex-col md:flex-row gap-2.5">
                    <div class="flex-1 flex items-center gap-3 px-3">
                        <i class="fas fa-search text-slate-400 text-xl"></i>
                        <input type="text" name="code" value="{{ $code ?? '' }}" required placeholder="Entrez votre code de suivi " 
                            class="w-full h-12 bg-transparent text-slate-800 font-bold placeholder-slate-400 focus:outline-none text-base">
                    </div>
                    <button type="submit" class="bg-brand-blue hover:bg-brand-blue-dark text-white font-black px-8 h-12 rounded-xl transition shadow-lg shadow-blue-200 whitespace-nowrap cursor-pointer flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-crosshairs"></i> Suivre votre Colis
                    </button>
                </form>
            </div>

            @if($colis)
            <div class="mt-12 bg-white rounded-3xl shadow-xl border border-slate-100 p-8 max-w-2xl mx-auto text-left transition-all duration-300">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6 mb-8">
                    <div>
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Référence Unique</span>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight mt-0.5">{{ $colis->code_suivi }}</h2>
                    </div>
                    
                    @php
                        $badgeColor = 'bg-slate-100 text-slate-700';
                        $statutDisplay = str_replace('_', ' ', $colis->statut);
                        
                        if(in_array(strtolower($colis->statut), ['livré', 'livre'])) {
                            $badgeColor = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                            $statutDisplay = 'Livré';
                        }
                        if(in_array(strtolower($colis->statut), ['en cours', 'en_cours'])) {
                            $badgeColor = 'bg-amber-50 text-amber-700 border border-amber-200';
                            $statutDisplay = 'En cours';
                        }
                        if(in_array(strtolower($colis->statut), ['retourné', 'retourne'])) {
                            $badgeColor = 'bg-rose-50 text-rose-700 border border-rose-200';
                            $statutDisplay = 'Retourné';
                        }
                        if(in_array(strtolower($colis->statut), ['annulé', 'annule'])) {
                            $badgeColor = 'bg-rose-50 text-rose-700 border border-rose-200';
                            $statutDisplay = 'Annulé';
                        }
                        if(in_array(strtolower($colis->statut), ['enregistre', 'ramassé', 'ramasse'])) {
                            $badgeColor = 'bg-blue-50 text-brand-blue border border-blue-200';
                            $statutDisplay = 'Ramassé';
                        }
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $badgeColor }}">
                        {{ $statutDisplay }}
                    </span>
                </div>

                @php
                    $currentStatut = strtolower($colis->statut);
                    
                    $isRamasse = in_array($currentStatut, ['enregistre', 'ramassé', 'ramasse', 'en cours', 'en_cours', 'livré', 'livre']);
                    $isEnCours = in_array($currentStatut, ['en cours', 'en_cours', 'livré', 'livre']);
                    $isFinal = in_array($currentStatut, ['livré', 'livre', 'retourné', 'retourne', 'annulé', 'annule']);
                    
                    $isChorfaNormal = !in_array($currentStatut, ['retourné', 'retourne', 'annulé', 'annule']);
                @endphp

                <div class="relative flex flex-col justify-between h-48 pl-8 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[3px] before:bg-slate-100 mb-8">
                    
                    <div class="absolute left-[11px] top-2 w-[3px] bg-brand-blue transition-all duration-500"
                         style="height: {{ $isFinal ? '100%' : ($isEnCours ? '50%' : ($isRamasse ? '0%' : '0%')) }}">
                    </div>

                    <div class="relative flex items-center gap-4">
                        <div class="absolute -left-[31px] w-6 h-6 rounded-full border-4 {{ $isRamasse ? 'bg-brand-blue border-blue-100 z-10' : 'bg-white border-slate-200' }} flex items-center justify-center transition-all">
                            @if($isRamasse) <i class="fas fa-check text-[9px] text-white"></i> @endif
                        </div>
                        <h4 class="text-sm font-black {{ $isRamasse ? 'text-slate-900' : 'text-slate-400' }}">Ramassé</h4>
                    </div>

                    <div class="relative flex items-center gap-4">
                        <div class="absolute -left-[31px] w-6 h-6 rounded-full border-4 {{ $isEnCours ? 'bg-brand-blue border-blue-100 z-10' : 'bg-white border-slate-200' }} flex items-center justify-center transition-all">
                            @if($isEnCours) <i class="fas fa-truck text-[9px] text-white"></i> @endif
                        </div>
                        <h4 class="text-sm font-black {{ $isEnCours ? 'text-slate-900' : 'text-slate-400' }}">En cours</h4>
                    </div>

                    @if($isChorfaNormal)
                        <div class="relative flex items-center gap-4">
                            <div class="absolute -left-[31px] w-6 h-6 rounded-full border-4 {{ $currentStatut == 'livré' || $currentStatut == 'livre' ? 'bg-emerald-500 border-emerald-100 z-10' : 'bg-white border-slate-200' }} flex items-center justify-center transition-all">
                                @if($currentStatut == 'livré' || $currentStatut == 'livre') <i class="fas fa-check text-[9px] text-white"></i> @endif
                            </div>
                            <h4 class="text-sm font-black {{ $currentStatut == 'livré' || $currentStatut == 'livre' ? 'text-emerald-600' : 'text-slate-400' }}">Livré</h4>
                        </div>
                    @else
                        <div class="relative flex items-center gap-4">
                            <div class="absolute -left-[31px] w-6 h-6 rounded-full border-4 bg-rose-500 border-rose-100 z-10 flex items-center justify-center">
                                <i class="fas fa-times text-[9px] text-white"></i>
                            </div>
                            <h4 class="text-sm font-black text-rose-600">
                                {{ in_array($currentStatut, ['annulé', 'annule']) ? 'Annulé' : 'Retourné' }}
                            </h4>
                        </div>
                    @endif
                </div>

                @if(in_array($currentStatut, ['livré', 'livre']))
                    <div class="border-t border-slate-100 pt-6 mt-6">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-alt text-brand-blue"></i> Laissez votre avis sur la livraison
                        </h3>
                        
                        @if(session('success'))
                            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('avis.store', $colis->token_suivi) }}" method="POST" class="space-y-3">
                            @csrf
                            <textarea name="commentaire" rows="3" required 
                                placeholder="Votre avis nous intéresse" 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue flex-1 resize-none"></textarea>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-brand-blue hover:bg-blue-800 text-white font-black px-4 py-2.5 rounded-xl text-[11px] tracking-wider uppercase transition shadow-xs cursor-pointer">
                                    Envoyer mon avis
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
            @endif
        </div>
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[1000px] h-[300px] bg-blue-50/40 rounded-full blur-3xl -z-10"></div>
    </header>

    <section id="services" class="py-20 bg-brand-blue relative">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h3 class="text-3xl font-black text-white">Les apports de la plateforme Shipily</h3>
                <p class="text-blue-200 text-sm mt-2 max-w-xl mx-auto font-medium">L'écosystème logistique moderne et intelligent pensé exclusivement pour les e-commerçants marocains.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                    <div class="w-12 h-12 bg-brand-orange text-white rounded-xl flex items-center justify-center text-xl mb-6 shadow-lg">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-3">Livraison Express Nationale</h4>
                    <p class="text-xs text-blue-100 leading-relaxed font-medium">Nous expédions et distribuons vos colis partout au Maroc sous un délai garanti de 24h à 48h maximum.</p>
                </div>
                <div class="p-8 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                    <div class="w-12 h-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center text-xl mb-6 shadow-lg">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-3">Gestion Flexible des Fonds</h4>
                    <p class="text-xs text-blue-100 leading-relaxed font-medium">Vos encaissements de livraison (Cash on Delivery) sont traités et versés de manière fluide et transparente sur votre compte.</p>
                </div>
                <div class="p-8 bg-white rounded-2xl border border-white shadow-xl">
                    <div class="w-12 h-12 bg-brand-blue text-white rounded-xl flex items-center justify-center text-xl mb-6 shadow-lg">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-3">Suivi Prédictif par IA</h4>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Un moteur d'intelligence artificielle intégré analyse le sentiment des avis clients pour anticiper et résoudre automatiquement les anomalies de livraison.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="workflow" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-brand-blue text-xs font-black uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-md">Flux de Travail</span>
                <h3 class="text-3xl font-black text-slate-900 mt-2">Comment fonctionne Shipily ?</h3>
                <p class="text-slate-400 text-sm mt-2">Un workflow automatisé et transparent de A à Z.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                <div class="text-center relative">
                    <div class="w-16 h-16 bg-blue-50 text-brand-blue rounded-2xl flex items-center justify-center text-2xl font-black mx-auto shadow-sm border border-blue-100 mb-4">1</div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Préparation & Dépôt</h4>
                    <p class="text-xs text-slate-400 max-w-[200px] mx-auto">L'E-commerçant crée le colis sur son tableau de bord et génère le code unique.</p>
                </div>

                <div class="text-center relative">
                    <div class="w-16 h-16 bg-blue-50 text-brand-blue rounded-2xl flex items-center justify-center text-2xl font-black mx-auto shadow-sm border border-blue-100 mb-4">2</div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Affectation Auto</h4>
                    <p class="text-xs text-slate-400 max-w-[200px] mx-auto">L'administrateur attribue intelligemment le colis au livreur de la zone concernée.</p>
                </div>

                <div class="text-center relative">
                    <div class="w-16 h-16 bg-blue-50 text-brand-blue rounded-2xl flex items-center justify-center text-2xl font-black mx-auto shadow-sm border border-blue-100 mb-4">3</div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Livraison Réelle</h4>
                    <p class="text-xs text-slate-400 max-w-[200px] mx-auto">Le livreur remet le colis, encaisse le montant (COD) et change le statut en temps réel.</p>
                </div>

                <div class="text-center relative">
                    <div class="w-16 h-16 bg-blue-50 text-brand-blue rounded-2xl flex items-center justify-center text-2xl font-black mx-auto shadow-sm border border-blue-100 mb-4">4</div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Wallet & Analyse IA</h4>
                    <p class="text-xs text-slate-400 max-w-[200px] mx-auto">Les fonds sont versés instantanément sur le portefeuille, et l'IA analyse l'avis du client.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white text-slate-400 py-12 border-t border-slate-100">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6 text-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-blue rounded-lg flex items-center justify-center relative overflow-hidden shadow-xs">
                    <div class="absolute inset-0 border-r-2 border-white/20 transform rotate-45 scale-150"></div>
                    <i class="fas fa-arrow-up text-brand-orange text-xs transform rotate-45"></i>
                </div>
                <span class="text-slate-900 font-black tracking-tight text-lg"><span class="text-brand-blue">Ship</span><span class="text-brand-orange">ily</span></span>
            </div>
            <p class="text-xs font-bold text-slate-400">© 2026 Shipily. Tous droits réservés. <span class="text-brand-blue ml-1">#SmartLogistics</span></p>
        </div>
    </footer>

</body>
</html>
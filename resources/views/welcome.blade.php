<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis - Transport & Livraison Nationale</title>
    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <!-- 🌟 NAVBAR PREMIUM -->
    <nav class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-white/90">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-box-open text-2xl text-indigo-600"></i>
                <span class="text-xl font-black text-slate-900 tracking-tight">MonColis<span class="text-indigo-600">.</span></span>
            </div>
            <!-- Menu Links -->
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#suivi" class="hover:text-indigo-600 transition">Suivre un Colis</a>
                <a href="#services" class="hover:text-indigo-600 transition">Nos Services</a>
                <a href="#tarifs" class="hover:text-indigo-600 transition">Tarifs</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="/login" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Connexion</a>
                <a href="/login" class="bg-indigo-600 text-white text-sm font-bold px-4 py-2 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">Espace Pro</a>
            </div>
        </div>
    </nav>

    <!-- 🚀 HERO SECTION + ESPACE SUIVI CENTRAL -->
    <header id="suivi" class="py-20 md:py-28 bg-gradient-to-b from-white to-slate-50 border-b border-slate-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <span class="bg-indigo-50 text-indigo-700 text-xs font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider border border-indigo-100">
                ⚡ Logistique Nationale Rapide
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 mt-6 tracking-tight leading-none">
                Vos colis livrés, <br class="hidden md:block"><span class="text-indigo-600">Où vous voulez, Quand vous voulez.</span>
            </h1>
            <p class="text-slate-500 mt-4 text-base md:text-lg max-w-xl mx-auto font-medium">
                Suivez votre expédition instantanément. Entrez le code de suivi de votre colis ci-dessous pour voir l'état de livraison.
            </p>

            <!-- 🔍 FORMULAIRE DE RECHERCHE D'EXPÉDITION -->
            <div class="mt-10 max-w-2xl mx-auto">
                <form action="/tracking" method="GET" class="bg-white p-2.5 rounded-2xl shadow-2xl border border-slate-100 flex flex-col md:flex-row gap-2">
                    <div class="flex-1 flex items-center gap-3 px-3">
                        <i class="fas fa-search text-slate-400 text-lg"></i>
                        <input type="text" name="code" value="{{ $code ?? '' }}" required placeholder="Entrez le code de suivi (Ex: MC-8893)" 
                            class="w-full h-12 bg-transparent text-slate-800 font-semibold placeholder-slate-400 focus:outline-none">
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 h-12 rounded-xl transition shadow-lg shadow-indigo-200 whitespace-nowrap">
                        <i class="fas fa-crosshairs mr-2"></i> Localiser le colis
                    </button>
                </form>
            </div>

            <!-- 📦 TIMELINE D'ÉTAT DYNAMIQUE -->
            @if(isset($code))
                <div class="mt-12 max-w-2xl mx-auto bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-2xl text-left transform transition-all duration-300 scale-100">
                    @if($colis)
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Référence du Colis</span>
                                <h4 class="text-lg font-black text-slate-800">{{ $colis->code_suivi }}</h4>
                            </div>
                            <span class="bg-indigo-50 text-indigo-700 text-xs font-black px-3 py-1.5 rounded-xl border border-indigo-100 uppercase tracking-wide shadow-sm">
                                <span class="w-2 h-2 bg-indigo-500 rounded-full inline-block mr-1.5 animate-pulse"></span>{{ $colis->statut }}
                            </span>
                        </div>

                        <!-- 🛤️ THE INTERACTIVE TIMELINE -->
                        <div class="relative pl-7 space-y-8 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            
                            <!-- Étape 1: Reçu -->
                            <div class="relative flex items-start gap-4">
                                <div class="absolute -left-7 w-4.5 h-4.5 rounded-full border-4 border-white shadow bg-emerald-500"></div>
                                <div>
                                    <h5 class="text-sm font-bold text-slate-800">Expédié par le marchand</h5>
                                    <p class="text-xs text-slate-400">Le colis a été bien enregistré sur la plateforme MonColis.</p>
                                </div>
                            </div>

                            <!-- Étape 2: En cours -->
                            <div class="relative flex items-start gap-4">
                                <div class="absolute -left-7 w-4.5 h-4.5 rounded-full border-4 border-white shadow {{ in_array($colis->statut, ['en_cours', 'livre']) ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                <div>
                                    <h5 class="text-sm font-bold {{ in_array($colis->statut, ['en_cours', 'livre']) ? 'text-slate-800' : 'text-slate-400' }}">En cours de livraison</h5>
                                    <p class="text-xs text-slate-400">Le colis est actuellement pris en charge par notre livreur de zone.</p>
                                </div>
                            </div>

                            <!-- Étape 3: Livré -->
                            <div class="relative flex items-start gap-4">
                                <div class="absolute -left-7 w-4.5 h-4.5 rounded-full border-4 border-white shadow {{ $colis->statut == 'livre' ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                <div>
                                    <h5 class="text-sm font-bold {{ $colis->statut == 'livre' ? 'text-slate-800' : 'text-slate-400' }}">Livré à destination 🎉</h5>
                                    <p class="text-xs text-slate-400">Colis remis en main propre au destinataire final avec succès.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- ⚠️ Erreur code inexistant -->
                        <div class="text-center py-6">
                            <div class="bg-rose-50 text-rose-500 w-12 h-12 rounded-full flex items-center justify-center mx-auto text-lg mb-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">Aucun colis trouvé</h4>
                            <p class="text-xs text-slate-400 mt-1">Ce code de suivi n'existe pas dans notre base de données. Veuillez réessayer.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </header>

    <!-- ✨ SECTION MARKETING 1: POURQUOI NOUS? -->
    <section id="services" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h3 class="text-2xl font-black text-slate-900">Pourquoi choisir MonColis ?</h3>
                <p class="text-slate-400 text-sm mt-1">La solution logistique moderne pour les e-commerçants marocains.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="fas fa-shipping-fast text-2xl text-indigo-500 mb-4 block"></i>
                    <h4 class="text-base font-bold text-slate-800 mb-2">Livraison Express</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Nous livrons vos colis partout au Maroc en moins de 24 à 48 heures maximum.</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="fas fa-coins text-2xl text-emerald-500 mb-4 block"></i>
                    <h4 class="text-base font-bold text-slate-800 mb-2">Retour de fonds rapide</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Vos encaissements de livraison cash-on-delivery sont versés sur votre compte en un temps record.</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="fas fa-headset text-2xl text-purple-500 mb-4 block"></i>
                    <h4 class="text-base font-bold text-slate-800 mb-2">Support Dédié</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Un service client réactif disponible pour gérer les réclamations et optimiser vos expéditions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 👣 FOOTER -->
    <footer class="bg-slate-900 text-slate-500 py-12 border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6 text-sm">
            <div class="flex items-center gap-2">
                <i class="fas fa-box-open text-indigo-500 text-xl"></i>
                <span class="text-white font-black tracking-tight">MonColis</span>
            </div>
            <p class="text-xs text-slate-400">© 2026 MonColis. Tous droits réservés. Réalisé pour une expérience logistique fluide.</p>
        </div>
    </footer>

</body>
</html>
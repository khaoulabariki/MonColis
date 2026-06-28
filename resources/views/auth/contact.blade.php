<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Shipily</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .text-brand-blue { color: #0A4BB3; }
        .bg-brand-blue { background-color: #0A4BB3; }
        .hover\:bg-brand-blue-dark:hover { background-color: #083D93; }
        .text-brand-orange { color: #FF6B00; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <nav class="bg-white border-b border-slate-100 h-20 flex items-center shadow-sm">
        <div class="max-w-7xl mx-auto px-6 w-full flex items-center justify-between">
            <a href="/tracking" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-blue rounded-xl flex items-center justify-center relative overflow-hidden">
                    <i class="fas fa-arrow-left text-white text-sm"></i>
                </div>
                <span class="text-sm font-bold text-slate-500 hover:text-brand-blue transition">Retour à l'accueil</span>
            </a>
            <span class="text-xl font-black text-brand-blue">Ship<span class="text-brand-orange">ily.</span></span>
        </div>
    </nav>

    <main class="py-16 max-w-5xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-brand-orange text-xs font-black uppercase tracking-widest bg-orange-50 px-3 py-1 rounded-md">Support Client</span>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mt-2">Contacter l'administration</h1>
            <p class="text-slate-400 text-sm mt-2 max-w-md mx-auto">Une question sur vos colis ou vos paiements ? Notre équipe vous répond dans les plus brefs délais.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 space-y-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg shadow-inner">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h5 class="text-xs text-slate-400 font-bold uppercase">Téléphone</h5>
                        <p class="text-sm font-bold text-slate-800">+212 5XX XX XX XX</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center text-lg shadow-inner">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h5 class="text-xs text-slate-400 font-bold uppercase">Email Support</h5>
                        <p class="text-sm font-bold text-slate-800">support@shipily.ma</p>
                    </div>
                </div>

                <a href="https://wa.me/21260000000" target="_blank" class="block bg-emerald-500 hover:bg-emerald-600 text-white p-5 rounded-2xl shadow-md transition text-center font-bold text-sm flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-xl"></i> Support WhatsApp Direct
                </a>
            </div>

            <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50">
                
                @if(session('success'))
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle text-base"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.post') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Nom Complet</label>
                            <input type="text" name="nom" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-sm font-semibold focus:outline-none focus:border-brand-blue text-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Email</label>
                            <input type="email" name="email" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-sm font-semibold focus:outline-none focus:border-brand-blue text-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Sujet de votre réclamation</label>
                        <input type="text" name="sujet" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-sm font-semibold focus:outline-none focus:border-brand-blue text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Message (Détails du problème)</label>
                        <textarea name="message" rows="5" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm font-semibold focus:outline-none focus:border-brand-blue text-slate-700 resize-none"></textarea>
                    </div>
                    <button type="submit" class="bg-brand-blue hover:bg-brand-blue-dark text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-md w-full sm:w-auto cursor-pointer">
                        Envoyer le message à l'administration
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
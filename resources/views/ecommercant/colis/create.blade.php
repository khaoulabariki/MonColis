<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily — Nouveau Colis</title>
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
                    <a href="{{ route('ecommercant.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-home w-5 text-center text-sm"></i> Mon Dashboard
                    </a>
                    <a href="{{ route('ecommercant.colis.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-box w-5 text-center text-sm"></i> Mes Colis
                    </a>
                    <a href="{{ route('ecommercant.colis.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-black bg-blue-50 text-brand-blue transition-all">
                        <i class="fas fa-plus-circle w-5 text-center text-sm"></i> Nouveau Colis
                    </a>
                    <a href="{{ route('ecommercant.finances') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
                        <i class="fas fa-wallet w-5 text-center text-sm"></i> Mon Wallet
                    </a>
                    <a href="{{ route('ecommercant.finances') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all">
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
            <div class="w-full max-w-3xl mx-auto">
                
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Enregistrer un Colis</h1>
                    <p class="text-xs font-medium text-slate-400 mt-1">Créez une nouvelle expédition en sélectionnant un destinataire pré-enregistré.</p>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4 mb-6 flex items-center gap-3 text-xs font-bold shadow-xs">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl p-4 mb-6 flex items-center gap-3 text-xs font-bold shadow-xs">
                        <i class="fas fa-exclamation-circle text-rose-500 text-sm"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 p-8">
                    <form method="POST" action="{{ route('ecommercant.colis.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Choisir un destinataire</label>
                            <div class="relative">
                                <select id="destinataire_select" name="destinataire_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition appearance-none cursor-pointer" required>
                                    <option value="" disabled selected>-- Sélectionnez un destinataire enregistré --</option>
                                    @foreach(auth()->user()->destinataires as $dest)
                                        <option value="{{ $dest->id }}" 
                                                data-nom="{{ $dest->nom }}" 
                                                data-prenom="{{ $dest->prenom }}" 
                                                data-phone="{{ $dest->telephone }}" 
                                                data-adresse="{{ $dest->adresse }}">
                                            {{ $dest->nom }} {{ $dest->prenom }} ({{ $dest->telephone }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 my-6 pt-4"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Nom destinataire</label>
                                <input type="text" id="nom_destinataire" name="nom_destinataire" value="{{ old('nom_destinataire') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition"
                                    readonly required>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Prénom destinataire</label>
                                <input type="text" id="prenom_destinataire" name="prenom_destinataire" value="{{ old('prenom_destinataire') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition"
                                    readonly required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Téléphone destinataire</label>
                            <input type="text" id="telephone_destinataire" name="telephone_destinataire" value="{{ old('telephone_destinataire') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition"
                                readonly required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Adresse de livraison</label>
                            <textarea id="adresse_destinataire" name="adresse_destinataire" rows="2"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition"
                                readonly required>{{ old('adresse_destinataire') }}</textarea>
                        </div>

                        <div class="border-t border-slate-100 my-6 pt-4"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Poids (kg)</label>
                                <div class="relative">
                                    <input type="number" name="poids" step="0.1" value="{{ old('poids') }}"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-brand-blue transition"
                                        required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Prix de vente (DH)</label>
                                <div class="relative">
                                    <input type="number" name="prix" step="0.01" value="{{ old('prix') }}"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-brand-blue transition"
                                        required>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-brand-blue hover:bg-brand-blue-dark text-white font-black py-3.5 rounded-xl transition duration-200 cursor-pointer shadow-xs text-sm uppercase tracking-wider">
                            <i class="fas fa-paper-plane mr-2"></i> Enregistrer le Colis
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('destinataire_select').addEventListener('change', function() {
            // جلب الـ option اللي تختار
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.value !== "") {
                // استخراج البيانات المخزنة ف الـ data attributes
                const nom = selectedOption.getAttribute('data-nom');
                const prenom = selectedOption.getAttribute('data-prenom');
                const phone = selectedOption.getAttribute('data-phone');
                const adresse = selectedOption.getAttribute('data-adresse');
                
                // تعبئة الحقول تلقائياً
                document.getElementById('nom_destinataire').value = nom;
                document.getElementById('prenom_destinataire').value = prenom;
                document.getElementById('telephone_destinataire').value = phone;
                document.getElementById('adresse_destinataire').value = adresse;
            }
        });
    </script>

</body>
</html>
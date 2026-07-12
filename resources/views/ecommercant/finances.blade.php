@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-wallet text-indigo-500 mr-2"></i>Mon Portefeuille Financier
            </h2>
            <p class="text-sm text-slate-500">Suivez votre solde disponible, vos gains réels et l'historique de vos colis livrés.</p>
        </div>
        <!-- Bouton pour ouvrir le Modal de retrait directement depuis le Header si besoin -->
        <div>
            <button onclick="openRetraitModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-sm transition flex items-center gap-2 cursor-pointer">
                <i class="fas fa-hand-holding-usd"></i> Demander un retrait
            </button>
        </div>
    </div>

    <!-- 📊 Financial Statistics Section (E-commerçant View) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- CARD 1: Solde Actuel Disponible (Calculated dynamically) -->
        <div class="bg-gradient-to-br from-indigo-50 to-white p-6 rounded-xl border border-indigo-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Solde Disponible</span>
                <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-vault"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-800">
                @php
                    $colisLivresCount = \App\Models\Colis::where('ecommercant_id', auth()->id())->where('statut', 'livre')->count();
                    $totalBrut = \App\Models\Colis::where('ecommercant_id', auth()->id())->where('statut', 'livre')->sum('prix');
                    $retraitsEnAttente = \App\Models\Retrait::where('ecommercant_id', auth()->id())->where('statut', 'en_attente')->sum('montant');
                    $soldeDynamique = ($wallet->solde ?? 0) - $retraitsEnAttente;
                @endphp
                {{ number_format($soldeDynamique, 2) }} <span class="text-lg font-bold text-slate-500">DH</span>
            </h3>
            <p class="text-[10px] text-indigo-600 font-medium mt-2">👉 Solde disponible prenant en compte vos retraits</p>
        </div>

        <!-- CARD 2: Chiffre d'Affaires Brut -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Chiffre d'Affaires Brut</span>
                <div class="w-8 h-8 rounded-full bg-slate-50 text-slate-600 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">
                {{ number_format($totalBrut, 2) }} <span class="text-sm font-bold text-slate-400">DH</span>
            </h3>
            <p class="text-[10px] text-slate-400 mt-1">Cumul total des ventes générées (Frais inclus)</p>
        </div>

        <!-- CARD 3: Nombre de Colis Livrés -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Colis Livrés</span>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">
                {{ $colisLivresCount }} 
                <span class="text-sm font-normal text-slate-500 ml-1">colis délivré(s)</span>
            </h3>
            <p class="text-[10px] text-emerald-600 font-medium mt-1">Taux de livraison réussi avec succès</p>
        </div>

    </div>

    <!-- 📋 TABLEAU 1 : HISTORIQUE DES GAINS ET ENCAISSEMENTS -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 bg-emerald-50/20 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-base"><i class="fas fa-check-double text-emerald-500 mr-2"></i>Détail de mes gains et frais par colis</h3>
                <p class="text-xs text-slate-400 mt-0.5">Retrouvez la liste de vos colis livrés ou retournés, et le montant net crédité ou débité sur votre solde.</p>
            </div>
            <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full">Automatique</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider">
                    <tr>
                        <th class="p-4">Code Colis</th>
                        <th class="p-4">Destinataire / Ville</th>
                        <th class="p-4">Prix du Colis</th>
                        <th class="p-4">Frais de Livraison</th>
                        <th class="p-4 text-right">Montant Net Crédité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse(\App\Models\Colis::where('ecommercant_id', auth()->id())->whereIn('statut', ['livre', 'retourne'])->orderBy('updated_at', 'desc')->get() as $colisEcom)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-semibold text-brand-blue">
                                #{{ $colisEcom->code_suivi }}
                                @if($colisEcom->statut === 'retourne')
                                    <span class="ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">Retourné</span>
                                @else
                                    <span class="ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Livré</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-700">
                                <div class="font-semibold">{{ $colisEcom->nom_destinataire }} {{ $colisEcom->prenom_destinataire }}</div>
                                <div class="text-[11px] text-slate-400"><i class="fas fa-map-marker-alt mr-1"></i>{{ $colisEcom->adresse_destinataire }}</div>
                            </td>
                            <td class="p-4 text-slate-400 font-bold">
                                @if($colisEcom->statut === 'retourne')
                                    <span class="line-through text-slate-300">{{ number_format($colisEcom->prix, 2) }} DH</span>
                                @else
                                    {{ number_format($colisEcom->prix, 2) }} DH
                                @endif
                            </td>
                            <td class="p-4 text-rose-500 font-semibold">- 50.00 DH</td>
                            <td class="p-4 text-right text-base">
                                @if($colisEcom->statut === 'livre')
                                    <span class="text-emerald-600 font-extrabold">+ {{ number_format($colisEcom->prix - 50, 2) }} DH</span>
                                @elseif($colisEcom->statut === 'retourne')
                                    <span class="text-rose-600 font-extrabold">- 50.00 DH</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-xs">Aucun colis livré ou retourné pour le moment. Votre solde augmentera dès qu'un colis sera validé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 📋 TABLEAU 2 : HISTORIQUE DES DEMANDES DE RETRAIT (NOUVEAU) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 bg-indigo-50/20">
            <h3 class="font-bold text-slate-800 text-base"><i class="fas fa-history text-indigo-500 mr-2"></i>Suivi de mes Demandes de Retrait</h3>
            <p class="text-xs text-slate-400 mt-0.5">Consultez l'état de traitement de vos demandes de virement de fonds en temps réel.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider">
                    <tr>
                        <th class="p-4">ID Demande</th>
                        <th class="p-4">Montant Demandé</th>
                        <th class="p-4">Date de Création</th>
                        <th class="p-4 text-center">Statut de Validation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <!-- Récupération dynamique de toutes les demandes de retrait de l'e-commerçant -->
                    @forelse(\App\Models\Retrait::where('ecommercant_id', auth()->id())->latest()->get() as $monRetrait)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-bold text-slate-700">#{{ $monRetrait->id }}</td>
                            <td class="p-4 font-extrabold text-slate-800 text-base">{{ number_format($monRetrait->montant, 2) }} DH</td>
                            <td class="p-4 text-slate-400 text-xs">{{ $monRetrait->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-center">
                                @if($monRetrait->statut === 'en_attente')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                        <i class="fas fa-hourglass-half mr-1 text-[10px]"></i> En attente
                                    </span>
                                @elseif($monRetrait->statut === 'valide')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <i class="fas fa-check-circle mr-1 text-[10px]"></i> Validée (Payée)
                                    </span>
                                @elseif($monRetrait->statut === 'rejete')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                        <i class="fas fa-times-circle mr-1 text-[10px]"></i> Refusée
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 text-xs">Vous n'avez soumis aucune demande de retrait pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- 💰 MODAL: DEMANDE DE RETRAIT (CONNECTED TO RETRAITCONTROLLER) -->
<div id="retraitModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md p-6 transform scale-95 transition-transform duration-300">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-money-check-alt text-indigo-500 mr-2"></i>Nouvelle Demande de Retrait</h3>
            <button onclick="closeRetraitModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <!-- Alert Message Container -->
        <div id="modalAlert" class="hidden p-3 rounded-xl text-xs font-semibold mb-4"></div>

        <!-- Submission Form linked to RetraitController@demanderRetrait -->
        <form id="retraitForm" onsubmit="submitRetrait(event)">
            @csrf
            
            <!-- Hidden Input to identify the current logged-in E-commercant -->
            <input type="hidden" name="ecommercant_id" id="ecommercant_id" value="{{ auth()->id() }}">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Montant à retirer (DH)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">DH</span>
                    <!-- Input name matches the validator in RetraitController -->
                    <input type="number" name="montant" id="montant" min="100" max="{{ $soldeDynamique ?? 0 }}" placeholder="Ex: 500" required
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-500 font-semibold text-slate-800">
                </div>
                <p class="text-[10px] text-slate-400 mt-1.5">Le montant minimum de retrait est fixé à 100.00 DH.</p>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center gap-3 justify-end border-t border-slate-100 pt-3">
                <button type="button" onclick="closeRetraitModal()" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-700 rounded-lg">Annuler</button>
                <button type="submit" id="submitBtn" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">Envoyer la demande</button>
            </div>
        </form>
    </div>
</div>

<!-- 📜 JAVASCRIPT SCRIPT TO CONTROL THE VUE & AJAX SUBMISSION -->
<script>
    function openRetraitModal() {
        const modal = document.getElementById('retraitModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeRetraitModal() {
        const modal = document.getElementById('retraitModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('retraitForm').reset();
            document.getElementById('modalAlert').classList.add('hidden');
        }, 300);
    }

    // Dynamic opening if URL contains ?action=retrait
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'retrait') {
            openRetraitModal();
        }
    });

    // Handle AJAX form submission to prevent raw JSON view
    function submitRetrait(event) {
        event.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const alertBox = document.getElementById('modalAlert');
        
        submitBtn.disabled = true;
        submitBtn.innerText = "Traitement...";

        const formData = {
            ecommercant_id: document.getElementById('ecommercant_id').value,
            montant: document.getElementById('montant').value,
            _token: '{{ csrf_token() }}'
        };

        // Submitting data using real route name
        fetch("/ecommercant/finances/retrait", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 201) {
                // Success action
                alertBox.className = "p-3 rounded-xl text-xs font-semibold mb-4 bg-green-50 text-green-700 border border-green-200";
                alertBox.innerText = res.body.message;
                alertBox.classList.remove('hidden');
                setTimeout(() => {
                    window.location.href = "{{ route('ecommercant.finances') }}";
                }, 1500);
            } else {
                // Controller errors (e.g. Insufficient balance)
                alertBox.className = "p-3 rounded-xl text-xs font-semibold mb-4 bg-red-50 text-red-700 border border-red-200";
                alertBox.innerText = res.body.message || "Une erreur est survenue.";
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerText = "Envoyer la demande";
            }
        })
        .catch(error => {
            alertBox.className = "p-3 rounded-xl text-xs font-semibold mb-4 bg-red-50 text-red-700 border border-red-200";
            alertBox.innerText = "Erreur de connexion au serveur.";
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerText = "Envoyer la demande";
        });
    }
</script>
@endsection
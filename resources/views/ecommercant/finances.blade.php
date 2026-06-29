@extends('layouts.app')

@section('content')
<style>
    .text-brand-blue { color: #0A4BB3; }
    .bg-brand-blue { background-color: #0A4BB3; }
    .hover\:bg-brand-blue-dark:hover { background-color: #083D93; }
    .text-brand-orange { color: #FF6B00; }
    .bg-brand-orange { background-color: #FF6B00; }
    .hover\:bg-brand-orange-dark:hover { background-color: #E05E00; }
</style>

<div class="w-full max-w-7xl mx-auto my-4">
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mon Portefeuille Financier</h1>
            <p class="text-xs font-medium text-slate-400 mt-1">Suivez votre solde disponible, vos gains réels et l'historique de vos demandes.</p>
        </div>
        <div>
            <button onclick="openRetraitModal()" class="bg-brand-orange hover:bg-brand-orange-dark text-white font-black px-5 py-3 rounded-xl text-xs tracking-wider uppercase transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fas fa-hand-holding-usd text-sm"></i> Demander un retrait
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="p-6 bg-white rounded-3xl border border-slate-200/60 shadow-xs flex flex-col justify-between min-h-[140px] border-b-4 border-b-brand-blue">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black text-brand-blue uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded-md">Solde Disponible</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-vault"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                @php
                    $colisLivresCount = \App\Models\Colis::where('ecommercant_id', auth()->id())->where('statut', 'livre')->count();
                    $totalBrut = \App\Models\Colis::where('ecommercant_id', auth()->id())->where('statut', 'livre')->sum('prix');
                    $soldeDynamique = $totalBrut - ($colisLivresCount * 50);
                @endphp
                {{ number_format($soldeDynamique, 2) }} <span class="text-sm font-black text-slate-400">DH</span>
            </h3>
            <p class="text-[11px] text-slate-400 font-medium mt-2"><i class="fas fa-circle-check text-brand-blue/70 mr-1"></i> Fonds calculés sur vos livraisons réussies</p>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-slate-200/60 shadow-xs flex flex-col justify-between min-h-[140px]">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded-md">Chiffre d'Affaires Brut</span>
                <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center text-sm">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                {{ number_format($totalBrut, 2) }} <span class="text-sm font-black text-slate-400">DH</span>
            </h3>
            <p class="text-[11px] text-slate-400 font-medium mt-2">Cumul total des ventes (Frais inclus)</p>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-slate-200/60 shadow-xs flex flex-col justify-between min-h-[140px] border-b-4 border-b-emerald-500">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black text-emerald-700 uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-md">Colis Livrés</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                {{ $colisLivresCount }} <span class="text-sm font-black text-slate-400">colis délivrés</span>
            </h3>
            <p class="text-[11px] text-emerald-600 font-medium mt-2"><i class="fas fa-arrow-trend-up mr-1"></i> Taux de livraison réussi avec succès</p>
        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden mb-8 w-full">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-black text-slate-900 text-sm uppercase tracking-wider"><i class="fas fa-check-double text-emerald-500 mr-2"></i>Détail de mes gains par colis</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Retrouvez la liste de vos colis livrés et le montant net crédité sur votre solde.</p>
            </div>
            <span class="text-[10px] bg-emerald-50 text-emerald-700 font-black px-2.5 py-1 rounded-md border border-emerald-100 uppercase tracking-wider self-start sm:self-center">Automatique</span>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="p-4 pl-6">Code Colis</th>
                        <th class="p-4">Destinataire / Ville</th>
                        <th class="p-4">Prix du Colis</th>
                        <th class="p-4">Frais de Livraison</th>
                        <th class="p-4 pr-6 text-right">Montant Net Crédité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-bold text-xs">
                    @forelse(\App\Models\Colis::where('ecommercant_id', auth()->id())->where('statut', 'livre')->orderBy('updated_at', 'desc')->get() as $colisEcom)
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="p-4 pl-6 font-black text-brand-blue font-mono">#{{ $colisEcom->code_suivi }}</td>
                            <td class="p-4 text-slate-900">
                                <div class="font-black">{{ $colisEcom->nom_destinataire }} {{ $colisEcom->prenom_destinataire }}</div>
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5"><i class="fas fa-map-marker-alt mr-1 text-slate-300"></i>{{ $colisEcom->adresse_destinataire }}</div>
                            </td>
                            <td class="p-4 text-slate-500 font-black">{{ number_format($colisEcom->prix, 2) }} DH</td>
                            <td class="p-4 text-rose-500 font-black">- 50.00 DH</td>
                            <td class="p-4 pr-6 text-emerald-600 font-black text-right text-sm">
                                + {{ number_format($colisEcom->prix - 50, 2) }} DH
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-slate-400 font-medium">
                                <div class="text-slate-200 text-2xl mb-1"><i class="fas fa-wallet"></i></div>
                                Aucun colis livré pour le moment. Votre solde augmentera dès qu'un colis sera validé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden mb-8 w-full">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-900 text-sm uppercase tracking-wider"><i class="fas fa-history text-brand-blue mr-2"></i>Suivi de mes Demandes de Retrait</h3>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Consultez l'état de traitement de vos demandes de virement de fonds en temps réel.</p>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="p-4 pl-6">ID Demande</th>
                        <th class="p-4">Montant Demandé</th>
                        <th class="p-4">Date de Création</th>
                        <th class="p-4 pr-6 text-center">Statut de Validation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-bold text-xs">
                    @forelse(\App\Models\Retrait::where('ecommercant_id', auth()->id())->latest()->get() as $monRetrait)
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="p-4 pl-6 font-black text-slate-700">#{{ $monRetrait->id }}</td>
                            <td class="p-4 font-black text-slate-900 text-sm">{{ number_format($monRetrait->montant, 2) }} DH</td>
                            <td class="p-4 text-slate-400 font-medium font-mono text-[11px]">{{ $monRetrait->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 pr-6 text-center">
                                @if($monRetrait->statut === 'en_attente')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-amber-50 text-amber-600 border border-amber-100">
                                        <i class="fas fa-hourglass-half mr-1"></i> En attente
                                    </span>
                                @elseif($monRetrait->statut === 'valide')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <i class="fas fa-check-circle mr-1"></i> Validée (Payée)
                                    </span>
                                @elseif($monRetrait->statut === 'rejete')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-rose-50 text-rose-600 border border-rose-100">
                                        <i class="fas fa-times-circle mr-1"></i> Refusée
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-400 font-medium">
                                <div class="text-slate-200 text-2xl mb-1"><i class="fas fa-comment-dollar"></i></div>
                                Vous n'avez soumis aucune demande de retrait pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="retraitModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 w-full max-w-md p-6 transform scale-95 transition-transform duration-300 mx-4">
        
        <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider"><i class="fas fa-money-check-alt text-brand-blue mr-2"></i>Demande de Retrait</h3>
            <button onclick="closeRetraitModal()" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
        </div>

        <div id="modalAlert" class="hidden p-3 rounded-xl text-xs font-bold mb-4"></div>

        <form id="retraitForm" onsubmit="submitRetrait(event)">
            @csrf
            
            <input type="hidden" name="ecommercant_id" id="ecommercant_id" value="{{ auth()->id() }}">

            <div class="mb-6">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Montant à retirer (DH)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-sm font-mono">DH</span>
                    <input type="number" name="montant" id="montant" min="10" max="{{ $soldeDynamique ?? 0 }}" placeholder="Ex: 500" required
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue font-black text-slate-800">
                </div>
                <p class="text-[10px] text-slate-400 font-medium mt-2">Le montant minimum de retrait est fixé à 10.00 DH.</p>
            </div>

            <div class="flex items-center gap-3 justify-end border-t border-slate-100 pt-4">
                <button type="button" onclick="closeRetraitModal()" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 rounded-lg cursor-pointer">Annuler</button>
                <button type="submit" id="submitBtn" class="px-5 py-2.5 text-xs font-black text-white bg-brand-blue hover:bg-brand-blue-dark rounded-xl shadow-xs uppercase tracking-wider transition cursor-pointer">Envoyer la demande</button>
            </div>
        </form>
    </div>
</div>

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

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'retrait') {
            openRetraitModal();
        }
    });

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
                alertBox.className = "p-3 rounded-xl text-xs font-bold mb-4 bg-emerald-50 text-emerald-800 border border-emerald-200";
                alertBox.innerText = res.body.message;
                alertBox.classList.remove('hidden');
                setTimeout(() => {
                    window.location.href = "{{ route('ecommercant.finances') }}";
                }, 1500);
            } else {
                alertBox.className = "p-3 rounded-xl text-xs font-bold mb-4 bg-rose-50 text-rose-800 border border-rose-200";
                alertBox.innerText = res.body.message || "Une erreur est survenue.";
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerText = "Envoyer la demande";
            }
        })
        .catch(error => {
            alertBox.className = "p-3 rounded-xl text-xs font-bold mb-4 bg-rose-50 text-rose-800 border border-rose-200";
            alertBox.innerText = "Erreur de connexion au serveur.";
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerText = "Envoyer la demande";
        });
    }
</script>
@endsection
@extends('layouts.app')

@section('content')
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
@endsection

@section('scripts')
<script>
    document.getElementById('destinataire_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption && selectedOption.value !== "") {
            const nom = selectedOption.getAttribute('data-nom');
            const prenom = selectedOption.getAttribute('data-prenom');
            const phone = selectedOption.getAttribute('data-phone');
            const adresse = selectedOption.getAttribute('data-adresse');
            
            document.getElementById('nom_destinataire').value = nom;
            document.getElementById('prenom_destinataire').value = prenom;
            document.getElementById('telephone_destinataire').value = phone;
            document.getElementById('adresse_destinataire').value = adresse;
        }
    });
</script>
@endsection
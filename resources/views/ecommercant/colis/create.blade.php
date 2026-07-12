@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        border-radius: 0.75rem !important;
        border-color: #e2e8f0 !important;
        background-color: #f8fafc !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        color: #334155 !important;
        box-shadow: none !important;
    }
    .ts-control.focus {
        border-color: #0A4BB3 !important;
        box-shadow: none !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem;
        border-color: #e2e8f0;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
    }
    .ts-dropdown .option {
        padding: 0.75rem 1rem;
    }
    .ts-dropdown .option.active {
        background-color: #eff6ff;
        color: #0A4BB3;
    }
</style>
@endsection

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
                <div>
                    <select id="destinataire_select" name="destinataire_id" placeholder="-- Sélectionnez ou recherchez (Nom, Prénom, Tél) --" autocomplete="off" class="w-full" required>
                        <option value="">-- Sélectionnez ou recherchez (Nom, Prénom, Tél) --</option>
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
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#destinataire_select", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

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
            } else {
                document.getElementById('nom_destinataire').value = "";
                document.getElementById('prenom_destinataire').value = "";
                document.getElementById('telephone_destinataire').value = "";
                document.getElementById('adresse_destinataire').value = "";
            }
        });
    });
</script>
@endsection
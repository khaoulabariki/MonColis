@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    {{-- 📋 En-tête de la page --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ __('Logistique & Distribution') }}</span><br>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                {{ __('Affectation des Colis') }}
            </h2>
            <p class="text-sm text-slate-400 font-medium mt-0.5">{{ __('Attribuez les colis en attente aux livreurs disponibles et gérez les tournées.') }}</p>
        </div>
    </div>

    {{-- 📊 Statistiques Rapides --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ __("En attente d'affectation") }}</span>
                <h3 class="text-2xl font-black text-amber-600 tracking-tight">
                    {{ \App\Models\Colis::whereIn('statut', ['enregistre', 'en_attente', 'reçu'])->count() }} <span class="text-sm font-bold text-slate-400">{{ __('Colis') }}</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-boxes"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ __('Déjà Assignés (En cours)') }}</span>
                <h3 class="text-2xl font-black text-[#0A4BB3] tracking-tight">
                    {{ \App\Models\Colis::where('statut', 'en_cours')->count() }} <span class="text-sm font-bold text-blue-400">{{ __('Colis') }}</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0A4BB3] flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-truck"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ __('Livreurs Actifs') }}</span>
                <h3 class="text-2xl font-black text-emerald-600 tracking-tight">
                    {{ \App\Models\Utilisateur::where('role', 'livreur')->count() }} <span class="text-sm font-bold text-emerald-400">{{ __('Livreurs') }}</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    {{-- Message Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200/60 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/40 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <span class="text-[10px] font-black text-[#0A4BB3] uppercase tracking-widest block mb-1">{{ __('Flux Logistique') }}</span> <br>
            <h3 class="font-black text-slate-900 text-xl tracking-tight">{{ __('Gestion des Assignations') }}</h3><br>
            <p class="text-xs font-medium text-slate-400 mt-0.5">{{ __("Sélectionnez un livreur pour chaque colis prêt pour l'expédition.") }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/40 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6 text-start">{{ __('Code Colis') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('E-commerçant') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Destination') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Prix') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Assigner à un Livreur') }}</th>
                        <th class="py-5 px-6 text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    {{-- التعديل هنا: كنقراو دابا من المتغير لي جاي من الكنترولر لي فيه حتى enregistre --}}
                    @forelse($colis as $item)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-5 px-6 font-mono font-black text-[#0A4BB3] text-sm">#{{ $item->code_suivi }}</td>
                            <td class="py-5 px-6 font-bold text-slate-800">
                                {{ $item->ecommercant->nom ?? __('Marchand') }} {{ $item->ecommercant->prenom ?? '' }}
                            </td>
                            <td class="py-5 px-6 text-slate-500">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-xs text-slate-700 font-bold">
                                    <i class="fas fa-map-marker-alt text-rose-500 me-1"></i>{{ $item->adresse_destinataire ?? __('Non spécifiée') }}
                                </span>
                            </td>
                            <td class="py-5 px-6 font-black text-slate-900">{{ number_format($item->prix, 2) }} DH</td>
                            
                            {{-- نموذج التعيين --}}
                            <form action="{{ route('admin.affectations.store', $item->id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                <td class="py-5 px-6">
                                    <select name="livreur_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-xs font-bold text-slate-700 focus:outline-none transition appearance-none cursor-pointer">
                                        <option value="">{{ __('Choisir un livreur...') }}</option>
                                        @foreach($livreurs as $livreur)
                                            <option value="{{ $livreur->id }}">{{ $livreur->nom }} {{ $livreur->prenom }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <button type="submit" class="bg-[#0A4BB3] hover:bg-[#083da3] text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition cursor-pointer shadow-md shadow-blue-900/10">
                                        <i class="fas fa-paper-plane me-1 rtl:-scale-x-100"></i> {{ __('Affecter') }}
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400 font-bold">
                                <i class="fas fa-box-open text-2xl block mb-2 text-slate-300"></i> {{ __("Aucun colis en attente d'affectation.") }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
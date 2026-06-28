@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    {{-- 📋 En-tête de la page --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Logistique & Distribution</span><br>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                Affectation des Colis
            </h2>
            <p class="text-sm text-slate-400 font-medium mt-0.5">Attribuez les colis en attente aux livreurs disponibles et gérez les tournées.</p>
        </div>
    </div>

    {{-- 📊 Statistiques Rapides d'Affectation --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">En attente d'affectation</span>
                <h3 class="text-2xl font-black text-amber-600 tracking-tight">
                    {{ \App\Models\Colis::where('statut', 'en_attente')->count() }} <span class="text-sm font-bold text-slate-400">Colis</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-boxes"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Déjà Assignés (En cours)</span>
                <h3 class="text-2xl font-black text-[#0A4BB3] tracking-tight">
                    {{ \App\Models\Colis::where('statut', 'en_cours')->count() }} <span class="text-sm font-bold text-blue-400">Colis</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0A4BB3] flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-truck"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Livreurs Actifs</span>
                <h3 class="text-2xl font-black text-emerald-600 tracking-tight">
                    {{ \App\Models\Utilisateur::where('role', 'livreur')->count() }} <span class="text-sm font-bold text-emerald-400">Livreurs</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/40 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <span class="text-[10px] font-black text-[#0A4BB3] uppercase tracking-widest block mb-1">Flux Logistique</span> <br>
            <h3 class="font-black text-slate-900 text-xl tracking-tight">Gestion des Assignations</h3><br>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Sélectionnez un livreur pour chaque colis prêt pour l'expédition.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/40 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6">Code Colis</th>
                        <th class="py-5 px-6">E-commerçant</th>
                        <th class="py-5 px-6">Destination</th>
                        <th class="py-5 px-6">Prix</th>
                        <th class="py-5 px-6">Assigner à un Livreur</th>
                        <th class="py-5 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    {{-- جلب الكوليز اللي فانتظار التوزيع أولاً --}}
                    @forelse(\App\Models\Colis::whereIn('statut', ['en_attente', 'reçu'])->orderBy('created_at', 'desc')->get() as $colis)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-5 px-6 font-mono font-black text-[#0A4BB3] text-sm">#{{ $colis->code_suivi }}</td>
                            <td class="py-5 px-6 font-bold text-slate-800">
                                @php $owner = \App\Models\Utilisateur::find($colis->ecommercant_id); @endphp
                                {{ $owner->nom ?? 'Marchand' }} {{ $owner->prenom ?? '' }}
                            </td>
                            <td class="py-5 px-6 text-slate-500">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-xs text-slate-700 font-bold">
                                    <i class="fas fa-map-marker-alt text-rose-500 mr-1"></i>{{ $colis->ville ?? 'Non spécifiée' }}
                                </span>
                            </td>
                            <td class="py-5 px-6 font-black text-slate-900">{{ number_format($colis->prix, 2) }} DH</td>
                            
                            {{-- نموذج التعيين --}}
                            <form action="{{ route('admin.affectations.store') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <input type="hidden" name="colis_id" value="{{ $colis->id }}">
                                
                                <td class="py-5 px-6">
                                    <select name="livreur_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-xs font-bold text-slate-700 focus:outline-none transition appearance-none cursor-pointer">
                                        <option value="">Choisir un livreur...</option>
                                        @foreach(\App\Models\Utilisateur::where('role', 'livreur')->get() as $livreur)
                                            <option value="{{ $livreur->id }}">{{ $livreur->nom }} {{ $livreur->prenom }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <button type="submit" class="bg-[#0A4BB3] hover:bg-[#083da3] text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition cursor-pointer shadow-md shadow-blue-900/10">
                                        <i class="fas fa-paper-plane mr-1"></i> Affecter
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400 font-bold">
                                <i class="fas fa-box-open text-2xl block mb-2 text-slate-300"></i> Aucun colis en attente d'affectation.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
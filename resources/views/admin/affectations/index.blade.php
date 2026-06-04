@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    <div class="flex justify-between items-center mb-6 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-hands-helping text-indigo-500"></i> Affectation des Colis aux Livreurs
            </h2>
            <p class="text-slate-500 text-sm mt-1">Attribuez facilement les colis en attente aux livreurs disponibles.</p>
        </div>
        <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-xs font-semibold px-3 py-1 rounded-full uppercase">
            {{ $colis->count() }} Colis en attente
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Code Suivi / Barre</th>
                        <th class="p-4">Destinataire & Ville</th>
                        <th class="p-4">Prix / Statut actuel</th>
                        <th class="p-4">Choisir le Livreur</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($colis as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono text-xs font-semibold text-slate-700">
                                <i class="fas fa-box text-slate-400 mr-2"></i>{{ $item->code_suivi }}
                            </td>
                            
                            <td class="p-4">
                                <div class="font-medium text-slate-800">{{ $item->nom_destinataire }} {{ $item->prenom_destinataire }}</div>
                                <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-map-marker-alt"></i> {{ $item->adresse_destinataire }}
                                </div>
                            </td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ number_format($item->prix, 2) }} DH</div>
                                <span class="inline-block text-[10px] px-2 py-0.5 font-semibold rounded-full mt-1 
                                    {{ $item->statut == 'enregistre' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                    {{ $item->statut }}
                                </span>
                            </td>
                            
                            <td class="p-4" colspan="2">
                                {{-- Ici on prépare le formulaire pour l'action future --}}
                                <form action="{{ route('admin.affectations.store', $item->id) }}" method="POST" class="flex items-center gap-2 m-0 p-0">
    @csrf
    <select name="livreur_id" required class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2 block w-full max-w-xs">
        <option value="">-- Sélectionner un livreur --</option>
        @foreach($livreurs as $livreur)
            <option value="{{ $livreur->id }}">{{ $livreur->nom }} {{ $livreur->prenom }} ({{ $livreur->telephone }})</option>
        @endforeach
    </select>
    
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs px-3 py-2 rounded-lg transition shadow-sm flex items-center gap-1 cursor-pointer">
        <i class="fas fa-check"></i> Affecter
    </button>
</form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-boxes text-3xl text-slate-300"></i>
                                    <span>Aucun colis en attente d'affectation pour le moment.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
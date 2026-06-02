@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">📦🚚 Affectation des Colis</h2>
        <p class="text-sm text-slate-500">Attribuez les nouveaux colis ou colis ramassés aux livreurs disponibles.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Code Suivi</th>
                        <th class="p-4">Destinataire</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4">Statut Actuel</th>
                        <th class="p-4">Choisir un Livreur</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($colis as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-semibold text-indigo-600">
                                #{{ $item->code_suivi }}
                            </td>
                            <td class="p-4 font-medium text-slate-800">
                                {{ $item->nom_destinataire }} {{ $item->prenom_destinataire }}
                            </td>
                            <td class="p-4 text-slate-500">
                                {{ $item->telephone_destinataire }}
                            </td>
                            <td class="p-4 text-slate-700 font-semibold">
                                {{ number_format($item->prix, 2) }} DH
                            </td>
                            <td class="p-4">
                                @if($item->statut == 'enregistre')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100">Enregistré</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-600 border border-purple-100">Ramassé</span>
                                @endif
                            </td>
                            
                            <form action="{{ url('/admin/affectations/store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="colis_id" value="{{ $item->id }}">
                                
                                <td class="p-4">
                                    <select name="livreur_id" required class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 focus:border-indigo-500 focus:bg-white outline-none transition text-slate-700 font-medium">
                                        <option value="" disabled selected>-- Sélectionner un Livreur --</option>
                                        @foreach($livreurs as $livreur)
                                            <option value="{{ $livreur->id }}">{{ $livreur->nom }} {{ $livreur->prenom }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-4 text-center">
                                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition shadow-sm flex items-center gap-1 mx-auto">
                                        <i class="fas fa-shipping-fast text-[10px]"></i> Affecter
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-check-double text-3xl text-slate-300"></i>
                                    <p class="text-sm">Tous les colis sont déjà affectés ou livrés ! ✨</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
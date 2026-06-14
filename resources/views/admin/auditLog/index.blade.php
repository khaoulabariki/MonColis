@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">📜 Audit Log (Journal d'Activités)</h2>
        <p class="text-sm text-slate-500">Historique complet des actions effectuées par les utilisateurs sur la plateforme.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Date & Heure</th>
                        <th class="p-4">Utilisateur</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Détails / Cible</th>
                        <th class="p-4 text-center">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($auditLogs ?? [] as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-medium text-slate-500">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            
                            <td class="p-4 font-semibold text-slate-800">
    @if(isset($log->utilisateur_id) && $log->utilisateur_id)
        @php
            $userObj = \App\Models\Utilisateur::find($log->utilisateur_id);
        @endphp
        
        @if($userObj)
            {{ $userObj->nom }} {{ $userObj->prenom }}
        @else
            Système
        @endif
    @else
        Système
    @endif
</td>        
                            
                            <td class="p-4">
                                <span class="font-medium text-slate-700">{{ $log->action }}</span>
                            </td>
                            
                            <td class="p-4 text-slate-500 max-w-xs truncate">
                                {{ $log->description ?? 'Aucun détail supplémentaire' }} </td>
                            
                            <td class="p-4 text-center">
                                @if($log->entite == 'LIVREUR')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">LIVREUR</span>
                                @elseif($log->entite == 'ECOMMERCANT')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">E-COM</span>
                                @elseif($log->entite == 'ADMIN')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-600 border border-purple-100">ADMIN</span>
                                @elseif(str_contains(strtolower($log->action), 'supprim') || str_contains(strtolower($log->action), 'delet'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">DELETE</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-50 text-slate-600 border border-slate-100">{{ $log->entite ?? 'INFO' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                //Message à afficher si aucun log n'est disponible
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-500">14/06/2026 23:14:11</td>
                            <td class="p-4 font-semibold text-slate-800">Sanaa Admin</td>
                            <td class="p-4 font-medium text-slate-700">SUPPRESSION_LIVREUR</td>
                            <td class="p-4 text-slate-500">L'administrateur a supprimé le compte du livreur : Ahmed Alami</td>
                            <td class="p-4 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">LIVREUR</span></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
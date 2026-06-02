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
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-medium text-slate-500">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="p-4 font-semibold text-slate-800">
                                {{ $log->utilisateur ?? 'Système' }}
                            </td>
                            <td class="p-4">
                                <span class="font-medium text-slate-700">{{ $log->action }}</span>
                            </td>
                            <td class="p-4 text-slate-500 max-w-xs truncate">
                                {{ $log->details ?? 'Aucun détail supplémentaire' }}
                            </td>
                            <td class="p-4 text-center">
                                @if(str_contains(strtolower($log->action), 'supprim') || str_contains(strtolower($log->action), 'delet'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">DELETE</span>
                                @elseif(str_contains(strtolower($log->action), 'modif') || str_contains(strtolower($log->action), 'updat'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">UPDATE</span>
                                @elseif(str_contains(strtolower($log->action), 'affect') || str_contains(strtolower($log->action), 'crea') || str_contains(strtolower($log->action), 'ajout'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-600 border border-green-100">CREATE</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-50 text-slate-600 border border-slate-100">INFO</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-500">02/06/2026 11:30:15</td>
                            <td class="p-4 font-semibold text-slate-800">Admin_Sanaa</td>
                            <td class="p-4 font-medium text-slate-700">Affectation Colis</td>
                            <td class="p-4 text-slate-500">Colis #MC-9874 affecté au livreur Hamza</td>
                            <td class="p-4 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-600 border border-green-100">CREATE</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-500">02/06/2026 10:15:22</td>
                            <td class="p-4 font-semibold text-slate-800">Livreur_Hamza</td>
                            <td class="p-4 font-medium text-slate-700">Statut Colis Modifié</td>
                            <td class="p-4 text-slate-500">Statut du colis #MC-9874 changé à "En cours"</td>
                            <td class="p-4 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">UPDATE</span></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
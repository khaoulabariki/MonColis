@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    
    {{-- 📋 En-tête de la page (Shipily Style) --}}
    <div class="mb-8">
        <span class="text-[10px] font-black text-[#0A4BB3] uppercase tracking-widest block mb-1">{{ __('Sécurité & Traçabilité') }}</span>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <i class="fas fa-history text-[#0A4BB3]"></i> {{ __('Audit Log') }}
        </h2>
        <p class="text-sm text-slate-400 font-medium mt-0.5">{{ __("Journal d'activités : Historique complet des actions effectuées par les utilisateurs sur la plateforme.") }}</p>
    </div>

    {{-- 🗂️ Tableau des Logs --}}
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6 text-start">{{ __('Date & Heure') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Utilisateur') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Action') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Détails / Cible') }}</th>
                        <th class="py-5 px-6 text-center">{{ __('Type') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-xs">
                    @forelse($auditLogs ?? [] as $log)
                        @php
                            $userObj = null;
                            if (isset($log->utilisateur_id) && $log->utilisateur_id) {
                                $userObj = \App\Models\Utilisateur::find($log->utilisateur_id);
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition">
                            {{-- Date --}}
                            <td class="py-5 px-6 font-mono text-slate-400">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            
                            {{-- Utilisateur --}}
                            <td class="py-5 px-6 font-bold text-slate-800">
                                <div class="flex items-center gap-2">
                                    @if($userObj)
                                        <div class="w-6 h-6 rounded-md bg-blue-50 text-[#0A4BB3] flex items-center justify-center text-[10px]">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <span>{{ $userObj->nom }} {{ $userObj->prenom }}</span>
                                    @else
                                        <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[10px]">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <span class="text-slate-400 italic">{{ __('Système') }}</span>
                                    @endif
                                </div>
                            </td>        
                            
                            {{-- Action --}}
                            <td class="py-5 px-6">
                                <span class="font-bold text-slate-700 font-mono text-[11px] uppercase bg-slate-100 px-2 py-1 rounded-md">
                                    {{ $log->action }}
                                </span>
                            </td>
                            
                            {{-- Description --}}
                            <td class="py-5 px-6 text-slate-500 max-w-xs truncate font-medium">
                                {{ $log->description ?? __('Aucun détail supplémentaire') }}
                            </td>
                            
                            {{-- Badge de Type (Entité) --}}
                            <td class="py-5 px-6 text-center">
                                @if($log->entite == 'LIVREUR')
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-amber-500 text-white uppercase tracking-wider shadow-sm shadow-amber-500/10">LIVREUR</span>
                                @elseif($log->entite == 'ECOMMERCANT')
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-[#0A4BB3] text-white uppercase tracking-wider shadow-sm shadow-[#0A4BB3]/10">E-COM</span>
                                @elseif($log->entite == 'ADMIN')
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-purple-600 text-white uppercase tracking-wider shadow-sm shadow-purple-600/10">ADMIN</span>
                                @elseif(str_contains(strtolower($log->action), 'supprim') || str_contains(strtolower($log->action), 'delet'))
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-rose-500 text-white uppercase tracking-wider shadow-sm shadow-rose-500/10">DELETE</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black bg-slate-600 text-white uppercase tracking-wider shadow-sm shadow-slate-600/10">{{ $log->entite ?? 'INFO' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- État vide professionnel --}}
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">
                                <i class="fas fa-shield-alt text-slate-300 text-3xl block mb-2"></i>
                                {{ __('Aucun log ou activité enregistré pour le moment.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
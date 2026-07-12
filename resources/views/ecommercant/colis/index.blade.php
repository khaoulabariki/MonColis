@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mes Colis</h1>
            <p class="text-xs font-medium text-slate-400 mt-1">Consultez et suivez l'état de l'ensemble de vos colis expédiés.</p>
        </div>
        <a href="{{ route('ecommercant.colis.create') }}"
            class="bg-brand-orange hover:bg-brand-orange-dark text-white font-black px-4 py-2.5 rounded-xl text-xs tracking-wider uppercase transition shadow-xs flex items-center gap-2 cursor-pointer">
            <i class="fas fa-plus text-[10px]"></i> Nouveau Colis
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4 mb-6 flex items-center gap-3 text-xs font-bold shadow-xs">
            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="p-4 pl-6">Code Suivi</th>
                        <th class="p-4">Destinataire</th>
                        <th class="p-4">Adresse</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4 pr-6">Statut</th> </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-bold">
                    @forelse($colis as $c)
                    <tr class="hover:bg-slate-50/40 transition">
                        <td class="p-4 pl-6 font-black text-brand-blue font-mono">{{ $c->code_suivi }}</td>
                        <td class="p-4 text-slate-900">{{ $c->prenom_destinataire }} {{ $c->nom_destinataire }}</td>
                        <td class="p-4 text-slate-500 font-medium max-w-xs truncate">{{ $c->adresse_destinataire }}</td>
                        <td class="p-4 font-black text-slate-900">{{ number_format($c->prix, 2) }} DH</td>
                        <td class="p-4 pr-6"> @php
                                $colors = [
                                    'enregistre' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'ramasse'    => 'bg-blue-50 text-brand-blue border-blue-100',
                                    'en_cours'   => 'bg-orange-50 text-brand-orange border-orange-100',
                                    'livre'      => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'retourne'   => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'annule'     => 'bg-rose-50 text-rose-700 border-rose-100',
                                ];

                                $labels = [
                                    'enregistre' => 'enregistré',
                                    'ramasse'    => 'ramassé',
                                    'en_cours'   => 'en cours',
                                    'livre'      => 'livré',
                                    'retourne'   => 'retourné',
                                    'annule'     => 'annulé',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-wide {{ $colors[$c->statut] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $labels[$c->statut] ?? str_replace('_', ' ', $c->statut) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 font-medium">
                            <div class="text-slate-300 text-2xl mb-2"><i class="fas fa-box-open"></i></div>
                            Aucun colis enregistré pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
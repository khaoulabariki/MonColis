@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Mes Livraisons') }}</h1>
        <p class="text-xs font-medium text-slate-400 mt-1">{{ __("Gérez et mettez à jour l'état de vos colis assignés en temps réel.") }}</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4 mb-6 flex items-center gap-3 text-xs font-bold shadow-xs">
            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-start border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="p-4 ps-6 text-start">{{ __('Code Suivi') }}</th>
                        <th class="p-4 text-start">{{ __('Destinataire') }}</th>
                        <th class="p-4 text-start">{{ __('Adresse') }}</th>
                        <th class="p-4 text-start">{{ __('Prix') }}</th>
                        <th class="p-4 text-start">{{ __('Statut') }}</th>
                        <th class="p-4 pe-6 text-start">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-bold">
                    @forelse($affectations as $affectation)
                    @php $c = $affectation->colis; @endphp
                    <tr class="hover:bg-slate-50/40 transition">
                        <td class="p-4 ps-6 font-black text-brand-blue font-mono">{{ $c->code_suivi }}</td>
                        <td class="p-4 text-slate-900">{{ $c->prenom_destinataire }} {{ $c->nom_destinataire }}</td>
                        <td class="p-4 text-slate-500 font-medium max-w-xs truncate">{{ $c->adresse_destinataire }}</td>
                        <td class="p-4 text-slate-900 font-mono font-black text-emerald-600">{{ number_format($c->prix, 2) }} DH</td>
                        <td class="p-4">
                            @php
                                $colors = [
                                    'enregistre' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'ramasse'    => 'bg-blue-50 text-brand-blue border-blue-100',
                                    'en_cours'   => 'bg-orange-50 text-brand-orange border-orange-100',
                                    'livre'      => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'retourne'   => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'annule'     => 'bg-rose-50 text-rose-700 border-rose-100',
                                ];

                                $labels = [
                                    'enregistre' => __('Enregistré'),
                                    'ramasse'    => __('Ramassé'),
                                    'en_cours'   => __('En cours'),
                                    'livre'      => __('Livré'),
                                    'retourne'   => __('Retourné'),
                                    'annule'     => __('Annulé'),
                                ];
                                
                                $currentStatut = $c->statut;
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-wide {{ $colors[$currentStatut] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $labels[$currentStatut] ?? str_replace('_', ' ', $currentStatut) }}
                            </span>
                        </td>
                        <td class="p-4 pe-6">
                            @if($c->statut !== 'livre' && $c->statut !== 'retourne')
                            <form method="POST" action="{{ route('livreur.colis.statut', ['id' => $c->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-2">
                                    <select name="statut" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-brand-blue transition">
                                        <option value="en_cours" {{ $c->statut == 'en_cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="livre" {{ $c->statut == 'livre' ? 'selected' : '' }}>{{ __('Livré') }}</option>
                                        <option value="retourne" {{ $c->statut == 'retourne' ? 'selected' : '' }}>{{ __('Retourné') }}</option>
                                    </select>
                                    <button type="submit" class="bg-brand-blue hover:bg-brand-blue-dark text-white font-black px-3 py-1.5 rounded-xl transition shadow-xs text-xs whitespace-nowrap cursor-pointer">
                                        <i class="fas fa-sync-alt me-1"></i> {{ __('Maj') }}
                                    </button>
                                </div>
                            </form>
                            @else
                                <span class="text-slate-400 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wide inline-flex items-center gap-1">
                                    <i class="fas fa-check text-[9px]"></i> {{ __('Terminé') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                            <div class="text-slate-300 text-2xl mb-2"><i class="fas fa-box-open"></i></div>
                            {{ __('Aucune livraison affectée pour le moment.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Mon Tableau de Bord') }}</h1>
    <p class="text-xs font-medium text-slate-400 mt-1">{{ __('Analyse approfondie des expéditions et de la satisfaction client.') }}</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">{{ __('Volume Expédié') }}</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-slate-900">{{ $totalColis ?? 0 }}</span>
            <span class="text-xs font-bold text-slate-400">{{ __('colis') }}</span>
        </div>
        <div class="mt-2 text-[11px] text-brand-blue font-bold">
            <i class="fas fa-box"></i> {{ __('Flux total de marchandises') }}
        </div>
    </div>

    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs border-b-4 border-b-emerald-500">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">{{ __('Succès de Livraison') }}</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-emerald-600">{{ $livres ?? 0 }}</span>
            <span class="text-xs font-bold text-emerald-500">{{ __('livrés') }}</span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400 font-medium">
            <i class="fas fa-check-circle text-emerald-500"></i> {{ __('Encaissés ou finalisés') }}
        </div>
    </div>

    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs border-b-4 border-b-orange-500">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">{{ __('Flux En Cours') }}</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-brand-orange">{{ $enCours ?? 0 }}</span>
            <span class="text-xs font-bold text-brand-orange">{{ __('sur la route') }}</span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400 font-medium animate-pulse">
            <i class="fas fa-truck text-brand-orange rtl:-scale-x-100"></i> {{ __('Chez les livreurs') }}
        </div>
    </div>

    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs border-b-4 border-b-rose-500">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">{{ __('Échecs & Retours') }}</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-rose-600">{{ $retournes ?? 0 }}</span>
            <span class="text-xs font-bold text-rose-500">{{ __('refusés') }}</span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400 font-medium">
            <i class="fas fa-undo text-rose-500"></i> {{ __('À réintégrer au stock') }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs lg:col-span-2">
        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-6">
            <i class="fas fa-chart-bar me-2 text-brand-blue"></i>{{ __('Répartition réelle des statuts colis') }}
        </h4>
        <div class="h-64">
            <canvas id="singleColisChart"></canvas>
        </div>
    </div>

    {{-- 🛠️ التعديل هنا: يرجع رمادي ومحايد 0% يلا كانت الـ داتا خاوية --}}
    @if(isset($recentAvis) && $recentAvis->count() > 0)
        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white px-2.5 py-1 rounded-lg">
                        <i class="fas fa-heart text-[9px] me-1"></i> {{ __('Avis & Sentiments') }}
                    </span>
                    <span class="text-2xl animate-bounce"> 😊 </span>
                </div>

                <h4 class="text-base font-black text-emerald-950 tracking-tight mb-2">{{ __('Humeur Générale') }}</h4>

                <p class="text-xs text-emerald-800 leading-relaxed font-medium">
                    {{ $rapportSentiment ?? __('Excellente tendance ! Les destinataires affichent un sentiment positif.') }}
                </p>
            </div>

            <div class="mt-6 pt-4 border-t border-emerald-200/60">
                <div class="flex justify-between text-xs font-black text-emerald-950 mb-1">
                    <span>{{ __('Score de satisfaction') }}</span>
                    <span>{{ $tauxSatisfaction ?? 100 }}%</span>
                </div>
                <div class="w-full bg-emerald-200 rounded-full h-2">
                    <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $tauxSatisfaction ?? 100 }}%"></div>
                </div>
            </div>
        </div>
    @else
        {{-- هاد الكارد الـ Neutre هو اللي غايبان دابا حيت كلشي خاوي 0 --}}
        <div class="bg-slate-50 border border-slate-200 p-6 rounded-3xl shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-slate-400 text-white px-2.5 py-1 rounded-lg">
                        <i class="fas fa-comment-slash text-[9px] me-1"></i> {{ __('Aucun Avis') }}
                    </span>
                    <span class="text-2xl">😶</span>
                </div>

                <h4 class="text-base font-black text-slate-700 tracking-tight mb-2">{{ __('Humeur Générale') }}</h4>

                <p class="text-xs text-slate-500 leading-relaxed font-medium italic">
                    {{ __("Aucune donnée disponible pour le moment. Les statistiques de satisfaction s'afficheront dès que vos clients laisseront des commentaires.") }}
                </p>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-200">
                <div class="flex justify-between text-xs font-black text-slate-400 mb-1">
                    <span>{{ __('Score de satisfaction') }}</span>
                    <span>0%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-slate-400 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    @endif
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const colors = {
            emerald: '#10B981',
            orange: '#FF6B00',
            rose: '#F43F5E'
        };

        // 📊 Single Unified Chart
        const ctx = document.getElementById('singleColisChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@json(__('Colis Livrés')), @json(__('En cours de route')), @json(__('Retours / Refusés'))],
                datasets: [{
                    label: @json(__('Volume')),
                    data: [{{ $livres ?? 0 }}, {{ $enCours ?? 0 }}, {{ $retournes ?? 0 }}],
                    backgroundColor: [colors.emerald, colors.orange, colors.rose],
                    borderRadius: 12,
                    barThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#F1F5F9' }, 
                        ticks: { font: { weight: 'bold', size: 10 }, color: '#94A3B8' } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { weight: 'bold', size: 11 }, color: '#475569' } 
                    }
                }
            }
        });
    });
</script>

<div class="mt-8 bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full p-6 text-start">
    <div class="mb-6">
        <h3 class="text-sm font-black text-slate-950 uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-star text-brand-orange"></i> {{ __('Avis sur vos Colis') }}
        </h3>
        <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ __('Retours de vos clients après la réception de leurs commandes.') }}</p>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="w-full text-start border-collapse text-sm text-slate-600">
            <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                <tr>
                    <th class="p-4 ps-6 text-start">{{ __('Code Colis') }}</th>
                    <th class="p-4 text-start">{{ __('Destinataire') }}</th>
                    <th class="p-4 text-start">{{ __('Avis Client') }}</th>
                    <th class="p-4 pe-6 text-start">{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-bold text-xs">
                @forelse($recentAvis ?? [] as $avis)
                    <tr class="hover:bg-slate-50/30 transition">
                        <td class="p-4 ps-6 text-brand-blue font-mono">{{ $avis->colis->code_suivi ?? 'N/A' }}</td>
                        <td class="p-4 text-slate-900 font-black">{{ $avis->colis->prenom_destinataire ?? '' }} {{ $avis->colis->nom_destinataire ?? __('Client') }}</td>
                        <td class="p-4 text-slate-500 font-medium max-w-md italic">"{{ $avis->commentaire }}"</td>
                        <td class="p-4 pe-6 text-slate-400 font-semibold">{{ $avis->created_at ? $avis->created_at->format('d/m/Y H:i') : '---' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400 font-medium">{{ __('Aucun avis laissé par vos clients pour le moment.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
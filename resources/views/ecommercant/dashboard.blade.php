@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mon Tableau de Bord</h1>
    <p class="text-xs font-medium text-slate-400 mt-1">Analyse approfondie des expéditions et de la satisfaction client.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Volume Expédié</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-slate-900">{{ $totalColis ?? 0 }}</span>
            <span class="text-xs font-bold text-slate-400">colis</span>
        </div>
        <div class="mt-2 text-[11px] text-brand-blue font-bold">
            <i class="fas fa-box"></i> Flux total de marchandises
        </div>
    </div>

    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs border-b-4 border-b-emerald-500">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Succès de Livraison</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-emerald-600">{{ $livres ?? 0 }}</span>
            <span class="text-xs font-bold text-emerald-500">livrés</span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400 font-medium">
            <i class="fas fa-check-circle text-emerald-500"></i> Encaissés ou finalisés
        </div>
    </div>

    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs border-b-4 border-b-orange-500">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Flux En Cours</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-brand-orange">{{ $enCours ?? 0 }}</span>
            <span class="text-xs font-bold text-brand-orange">sur la route</span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400 font-medium animate-pulse">
            <i class="fas fa-truck text-brand-orange"></i> Chez les livreurs
        </div>
    </div>

    <div class="p-5 bg-white rounded-3xl border border-slate-200/60 shadow-xs border-b-4 border-b-rose-500">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Échecs & Retours</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-black text-rose-600">{{ $retournes ?? 0 }}</span>
            <span class="text-xs font-bold text-rose-500">refusés</span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400 font-medium">
            <i class="fas fa-undo text-rose-500"></i> À réintégrer au stock
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-xs lg:col-span-2">
        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-6">
            <i class="fas fa-chart-bar mr-2 text-brand-blue"></i>Répartition réelle des statuts colis
        </h4>
        <div class="h-64">
            <canvas id="singleColisChart"></canvas>
        </div>
    </div>

    <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white px-2.5 py-1 rounded-lg">
                    <i class="fas fa-heart text-[9px] mr-1"></i> Avis & Sentiments
                </span>
                <span class="text-2xl animate-bounce">✨ 😊 ✨</span>
            </div>
            
            <h4 class="text-base font-black text-emerald-950 tracking-tight mb-2">Humeur Générale</h4>
            
            <p class="text-xs text-emerald-800 leading-relaxed font-medium">
                {{ $rapportSentiment ?? "Excellente tendance ! 84% de vos destinataires affichent un sentiment très positif. Les mots qui reviennent le plus : 'Livreur poli', 'Emballage intact' et 'Service rapide'. Attention toutefois à 2 retours négatifs concernant des appels tardifs." }}
            </p>
        </div>

        <div class="mt-6 pt-4 border-t border-emerald-200/60">
            <div class="flex justify-between text-xs font-black text-emerald-950 mb-1">
                <span>Score de satisfaction</span>
                <span>88%</span>
            </div>
            <div class="w-full bg-emerald-200 rounded-full h-2">
                <div class="bg-emerald-600 h-2 rounded-full" style="width: 88%"></div>
            </div>
        </div>
    </div>
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
                labels: ['Colis Livrés', 'En cours de route', 'Retours / Refusés'],
                datasets: [{
                    label: 'Volume',
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
<div class="mt-8 bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full p-6 text-left">
    <div class="mb-6">
        <h3 class="text-sm font-black text-slate-950 uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-star text-brand-orange"></i> Avis sur vos Colis
        </h3>
        <p class="text-[11px] font-medium text-slate-400 mt-0.5">Retours de vos clients après la réception de leurs commandes.</p>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse text-sm text-slate-600">
            <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                <tr>
                    <th class="p-4 pl-6">Code Colis</th>
                    <th class="p-4">Destinataire</th>
                    <th class="p-4">Avis Client</th>
                    <th class="p-4 pr-6">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-bold text-xs">
                @forelse($recentAvis as $avis)
                    <tr class="hover:bg-slate-50/30 transition">
                        <td class="p-4 pl-6 text-brand-blue font-mono">{{ $avis->colis->code_suivi ?? 'N/A' }}</td>
                        <td class="p-4 text-slate-900 font-black">{{ $avis->colis->prenom_destinataire ?? '' }} {{ $avis->colis->nom_destinataire ?? 'Client' }}</td>
                        <td class="p-4 text-slate-500 font-medium max-w-md italic">"{{ $avis->commentaire }}"</td>
                        <td class="p-4 pr-6 text-slate-400 font-semibold">{{ $avis->created_at ? $avis->created_at->format('d/m/Y H:i') : '---' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400 font-medium">Aucun avis laissé par vos clients pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
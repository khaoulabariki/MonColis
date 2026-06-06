@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">👑 Tableau de Bord Admin</h2>
    <p class="text-sm text-slate-500">Analyse, statistiques et intelligence artificielle en temps réel.</p>
</div>

<!-- 📦 Les Cartes des Statistiques (KPIs Premium) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-t-4 border-t-blue-500">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Colis</span>
        <h3 class="text-3xl font-black text-blue-600 mt-1">{{ $totalColis ?? 0 }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-t-4 border-t-emerald-500">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Livrés</span>
        <h3 class="text-3xl font-black text-emerald-600 mt-1">{{ $livres ?? 0 }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-t-4 border-t-amber-500">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">En Cours</span>
        <h3 class="text-3xl font-black text-amber-500 mt-1">{{ $enCours ?? 0 }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-t-4 border-t-rose-500">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Retournés</span>
        <h3 class="text-3xl font-black text-rose-600 mt-1">{{ $retournes ?? 0 }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- 📊 1. GRAPHIQUE PREMIUM (Doughnut) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 lg:col-span-2">
        <h3 class="text-sm font-bold text-slate-700 mb-4"><i class="fas fa-chart-pie mr-2 text-indigo-500"></i>Ventilation Graphique des Colis</h3>
        <div class="h-64 flex justify-center">
            <canvas id="colisChart"></canvas>
        </div>
    </div>

    <!-- 🤖 2. MODULE IA INTERNE (Régler & Protéger) -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-xl border border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 border-b border-slate-800 pb-3 mb-4">
                <i class="fas fa-robot text-lg text-indigo-400 animate-pulse"></i>
                <h3 class="text-sm font-bold tracking-wide">Rapport IA Automatique</h3>
            </div>
            
            <div class="space-y-4">
                <!-- Taux de Satisfaction -->
                <div class="flex items-center justify-between p-3 bg-slate-800/40 rounded-xl border border-slate-800">
                    <span class="text-xs text-slate-400 uppercase font-bold">Satisfaction</span>
                    <span class="text-2xl font-extrabold text-indigo-400">{{ $tauxSatisfaction ?? 100 }}%</span>
                </div>
                
                <!-- Analyse des avis -->
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase block">Analyse des avis :</span>
                    <div class="text-xs text-slate-300 leading-relaxed bg-slate-800/70 p-3 rounded-xl border border-slate-800 font-mono min-h-[110px]">
                        "{{ $iaResume ?? 'Aucun avis traité.' }}"
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-[10px] text-slate-500 text-right mt-4">
            Total: {{ $totalAvis ?? 0 }} avis analysés
        </div>
    </div>
</div>

<!-- 📋 Importation et configuration de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('colisChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Livrés', 'En cours', 'Retournés', 'Annulés'],
                datasets: [{
                    data: [{{ $livres ?? 0 }}, {{ $enCours ?? 0 }}, {{ $retournes ?? 0 }}, {{ $annules ?? 0 }}],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>

@endsection
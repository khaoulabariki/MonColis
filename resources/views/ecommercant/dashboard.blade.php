@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">📊 Mon Tableau de Bord</h2>
    <p class="text-sm text-slate-500">Suivez l'état de vos expéditions en temps réel.</p>
</div>

<!--  Les Cartes des Statistiques (KPIs) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Colis</span>
        <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalColis ?? 0 }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-emerald-500">
        <span class="text-xs text-emerald-500 font-bold uppercase tracking-wider">Livrés</span>
        <h3 class="text-3xl font-black text-emerald-600 mt-1">{{ $livres ?? 0 }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-amber-500">
        <span class="text-xs text-amber-500 font-bold uppercase tracking-wider">En Cours</span>
        <h3 class="text-3xl font-black text-amber-600 mt-1">{{ $enCours ?? 0 }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-rose-500">
        <span class="text-xs text-rose-500 font-bold uppercase tracking-wider">Retournés</span>
        
        <h3 class="text-3xl font-black text-rose-600 mt-1">{{ $retournes ?? 0 }}</h3>
    </div>
</div>

<!--  SECTION DES GRAPHIQUES -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- 1. Graphique En Barre -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h4 class="text-sm font-bold text-slate-700 mb-4"><i class="fas fa-chart-bar mr-2 text-indigo-500"></i>Volume des expéditions</h4>
        <div class="h-64">
            <canvas id="colisBarChart"></canvas>
        </div>
    </div>

    <!-- 2. Graphique Circulaire -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h4 class="text-sm font-bold text-slate-700 mb-4"><i class="fas fa-chart-pie mr-2 text-purple-500"></i>Répartition par Statut</h4>
        <div class="h-64 flex justify-center">
            <canvas id="colisPieChart"></canvas>
        </div>
    </div>
</div>

<!-- Importation de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 📊 1. Bar Chart
        const ctxBar = document.getElementById('colisBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Livrés', 'En cours', 'Retournés'],
                datasets: [{
                    label: 'Nombre de Colis',
                    
                    data: [{{ $livres ?? 0 }}, {{ $enCours ?? 0 }}, {{ $retournes ?? 0 }}],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderRadius: 8,
                    barThickness: 25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 🍩 2. Doughnut Chart
        const ctxPie = document.getElementById('colisPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Livrés', 'En cours', 'Retournés'],
                datasets: [{
                    
                    data: [{{ $livres ?? 0 }}, {{ $enCours ?? 0 }}, {{ $retournes ?? 0 }}],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>
@endsection
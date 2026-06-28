@extends('layouts.app')

@section('content')
<div class="mb-10">
    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-1.5">Tableau de Bord · Admin</span>
    <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
     <span><span class="text-[#0A4BB3]">Ship</span><span class="text-[#FF6B00]">ily</span></span>
    </h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    
    <div class="bg-[#0A4BB3] text-white p-7 rounded-3xl shadow-lg shadow-blue-900/10 flex flex-col justify-between min-h-[210px]">
        <div>
            <div class="flex justify-between items-center mb-5">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white"><i class="fas fa-box text-sm"></i></div>
                <span class="text-[10px] font-bold bg-white/20 px-2.5 py-1 rounded-full text-white/90 tracking-wide">+12%</span>
            </div>
            <span class="text-4xl font-black tracking-tight block mb-1.5">{{ $totalColis ?? 0 }}</span>
            <span class="text-[11px] font-black tracking-wider uppercase text-blue-200">Colis Total ce mois</span>
        </div>
        <div class="flex items-end gap-2 h-8 mt-5 opacity-40">
            <div class="bg-white w-full h-3 rounded-xs"></div>
            <div class="bg-white w-full h-5 rounded-xs"></div>
            <div class="bg-white w-full h-4 rounded-xs"></div>
            <div class="bg-white w-full h-7 rounded-xs"></div>
            <div class="bg-white w-full h-6 rounded-xs"></div>
            <div class="bg-white w-full h-9 rounded-xs bg-white/90"></div>
        </div>
    </div>

    <div class="bg-[#10B981] text-white p-7 rounded-3xl shadow-lg shadow-emerald-950/10 flex flex-col justify-between min-h-[210px]">
        <div>
            <div class="flex justify-between items-center mb-5">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white"><i class="fas fa-check-circle text-sm"></i></div>
                <span class="text-[10px] font-bold bg-white/20 px-2.5 py-1 rounded-full text-white/90 tracking-wide">+8.3%</span>
            </div>
            <span class="text-4xl font-black tracking-tight block mb-1.5">{{ $livres ?? 0 }}</span>
            <span class="text-[11px] font-black tracking-wider uppercase text-emerald-100">Livrés avec succès</span>
        </div>
        <div class="flex items-end gap-2 h-8 mt-5 opacity-40">
            <div class="bg-white w-full h-4 rounded-xs"></div>
            <div class="bg-white w-full h-6 rounded-xs"></div>
            <div class="bg-white w-full h-5 rounded-xs"></div>
            <div class="bg-white w-full h-7 rounded-xs"></div>
            <div class="bg-white w-full h-6 rounded-xs"></div>
            <div class="bg-white w-full h-9 rounded-xs bg-white/90"></div>
        </div>
    </div>

    <div class="bg-[#FF6B00] text-white p-7 rounded-3xl shadow-lg shadow-orange-950/10 flex flex-col justify-between min-h-[210px]">
        <div>
            <div class="flex justify-between items-center mb-5">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white"><i class="fas fa-truck text-sm"></i></div>
                <span class="text-[10px] font-bold bg-white/20 px-2.5 py-1 rounded-full text-white/90 tracking-wide">En cours</span>
            </div>
            <span class="text-4xl font-black tracking-tight block mb-1.5">{{ $enCours ?? 0 }}</span>
            <span class="text-[11px] font-black tracking-wider uppercase text-orange-100">En cours de livraison</span>
        </div>
        <div class="flex items-end gap-2 h-8 mt-5 opacity-40">
            <div class="bg-white w-full h-6 rounded-xs"></div>
            <div class="bg-white w-full h-7 rounded-xs"></div>
            <div class="bg-white w-full h-4 rounded-xs"></div>
            <div class="bg-white w-full h-8 rounded-xs"></div>
            <div class="bg-white w-full h-6 rounded-xs"></div>
            <div class="bg-white w-full h-9 rounded-xs bg-white/90"></div>
        </div>
    </div>

    <div class="bg-[#EF4444] text-white p-7 rounded-3xl shadow-lg shadow-rose-950/10 flex flex-col justify-between min-h-[210px]">
        <div>
            <div class="flex justify-between items-center mb-5">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white"><i class="fas fa-undo-alt text-sm"></i></div>
                <span class="text-[10px] font-bold bg-white/20 px-2.5 py-1 rounded-full text-white/90 tracking-wide">-2.1%</span>
            </div>
            <span class="text-4xl font-black tracking-tight block mb-1.5">{{ $retournes ?? 0 }}</span>
            <span class="text-[11px] font-black tracking-wider uppercase text-rose-100">Retournés / Refusés</span>
        </div>
        <div class="flex items-end gap-2 h-8 mt-5 opacity-40">
            <div class="bg-white w-full h-5 rounded-xs"></div>
            <div class="bg-white w-full h-4 rounded-xs"></div>
            <div class="bg-white w-full h-6 rounded-xs"></div>
            <div class="bg-white w-full h-3 rounded-xs"></div>
            <div class="bg-white w-full h-5 rounded-xs"></div>
            <div class="bg-white w-full h-9 rounded-xs bg-white/90"></div>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <div class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-xs flex flex-col justify-between min-h-[380px]">
        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Ventilation Graphique des Colis</h3>
        <div class="h-64 w-full flex justify-center items-center">
            <canvas id="colisChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-xs flex flex-col justify-between min-h-[380px]">
        <div>
            <div class="flex items-center justify-between mb-8">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center"><i class="fas fa-heart-pulse text-lg"></i></div>
                <span class="text-[10px] font-black bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full tracking-wide">IA Sentiment</span>
            </div>
            
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Satisfaction Clients</h3>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-8">
                <div class="w-36 h-36 relative flex items-center justify-center shrink-0">
                    <canvas id="satisfactionChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-slate-900 tracking-tighter">{{ $tauxSatisfaction ?? 72 }}%</span>
                    </div>
                </div>
                <div class="space-y-2.5 w-full">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100"><span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span> Positif</span> <span>72%</span></div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100"><span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#0A4BB3]"></span> Neutre</span> <span>18%</span></div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100 text-rose-600"><span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span> Négatif</span> <span>10%</span></div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col gap-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Résumé d'Analyse Automatique :</span>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs text-slate-600 font-medium italic">
                "{{ $iaResume ?? 'Aucun avis critique détecté aujourd\'hui.' }}"
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Chart: Ventilation des Colis
        const ctxColis = document.getElementById('colisChart').getContext('2d');
        new Chart(ctxColis, {
            type: 'doughnut',
            data: {
                labels: ['Livrés', 'En cours', 'Retournés', 'Annulés'],
                datasets: [{
                    data: [{{ $livres ?? 0 }}, {{ $enCours ?? 0 }}, {{ $retournes ?? 0 }}, {{ $annules ?? 0 }}],
                    backgroundColor: ['#10B981', '#FF6B00', '#EF4444', '#94A3B8'],
                    borderWidth: 4,
                    borderColor: '#ffffff'
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15, font: { family: 'Inter', weight: 'bold', size: 11 } }
                    }
                }
            }
        });

        // 2. Chart: IA Satisfaction
        const ctxSat = document.getElementById('satisfactionChart').getContext('2d');
        new Chart(ctxSat, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [72, 18, 10],
                    backgroundColor: ['#10B981', '#0A4BB3', '#EF4444'],
                    borderWidth: 4,
                    borderColor: '#ffffff'
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                cutout: '80%', 
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection
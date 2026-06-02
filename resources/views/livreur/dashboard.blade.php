<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonColis - Administration</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div class="flex min-h-screen">
        
        <!-- SIDEBAR DIRECTE -->
        <div class="w-64 bg-slate-900 text-slate-300 flex flex-col shadow-xl">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-box-open text-indigo-500"></i> MonColis
                </h1>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="/admin/dashboard" class="block px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium transition"><i class="fas fa-chart-pie mr-2"></i>Dashboard</a>
                <a href="/admin/colis" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-boxes mr-2"></i>Colis</a>
                <a href="/admin/users" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-users mr-2"></i>Utilisateurs</a>
                <a href="/admin/livreurs" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-truck mr-2"></i>Livreurs</a>
                <a href="/admin/ecommercants" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-store mr-2"></i>E-commerçants</a>
                <a href="/admin/affectations" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-route mr-2"></i>Affectations</a>
                <a href="/admin/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-wallet mr-2"></i>Finances</a>
                <a href="/admin/audit" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-history mr-2"></i>Audit Log</a>
            </nav>
        </div>

        <!-- CONTENU -->
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Tableau de bord</h2>
                    <p class="text-sm text-slate-500">Analyse et statistiques en temps réel.</p>
                </div>
            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <span class="text-xs text-slate-400 font-bold uppercase">Total Colis</span>
                    <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ $totalColis ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <span class="text-xs text-slate-400 font-bold uppercase">Livrés</span>
                    <p class="text-3xl font-extrabold text-green-600 mt-2">{{ $livres ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <span class="text-xs text-slate-400 font-bold uppercase">En cours</span>
                    <p class="text-3xl font-extrabold text-amber-500 mt-2">{{ $enCours ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                    <span class="text-xs text-slate-400 font-bold uppercase">Retournés</span>
                    <p class="text-3xl font-extrabold text-rose-600 mt-2">{{ $retournes ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-8">
                <!-- GRAPHIQUE -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 col-span-2">
                    <h3 class="text-base font-bold text-slate-800 mb-4">📊 Ventilation Graphique des Colis</h3>
                    <div style="height: 240px; position: relative;">
                        <canvas id="colisChart"></canvas>
                    </div>
                </div>

                <!-- 🤖 MODULE IA INTERNE -->
                <div class="bg-slate-900 text-white rounded-xl p-6 shadow-xl border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 border-b border-slate-800 pb-3 mb-4">
                            <i class="fas fa-robot text-lg text-indigo-400"></i>
                            <h3 class="text-sm font-bold">Rapport IA Automatique</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-slate-800/40 rounded-xl border border-slate-800">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Satisfaction</span>
                                <span class="text-2xl font-extrabold text-indigo-400">{{ $tauxSatisfaction ?? 100 }}%</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Analyse des avis :</span>
                                <div class="text-xs text-slate-300 leading-relaxed bg-slate-800/70 p-3 rounded-xl border border-slate-800 font-mono min-h-[100px]">
                                    "{{ $iaResume ?? 'Aucun avis traité.' }}"
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-[10px] text-slate-500 text-right mt-2">
                        Total: {{ $totalAvis ?? 0 }} avis
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT GRAPH -->
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
                        backgroundColor: ['#22c55e', '#f59e0b', '#ef4444', '#6b7280']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</body>
</html>
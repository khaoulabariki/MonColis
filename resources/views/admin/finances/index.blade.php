@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800"><i class="fas fa-wallet text-indigo-500 mr-2"></i>Suivi des Finances</h2>
        <p class="text-sm text-slate-500">Visualisez le chiffre d'affaires, les gains de la plateforme et les montants e-commerçants.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Collecté (Cash)</span>
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fas fa-money-bill-wave"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalCollecte ?? 0 }} DH</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gains Plateforme</span>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fas fa-chart-line"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">{{ $fraisLivraisonTotal ?? 0 }} DH</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Solde E-commerçants</span>
                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fas fa-hand-holding-usd"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">{{ $soldeEcommerçants ?? 0 }} DH</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Détails des Colis Livrés (Simulés pour Test)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px]">
                    <tr>
                        <th class="p-4">Code Colis</th>
                        <th class="p-4">Prix du Colis</th>
                        <th class="p-4">Frais Livraison</th>
                        <th class="p-4">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($colisLivres ?? [] as $colis)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-semibold text-indigo-600">#{{ $colis->id }}</td>
                            <td class="p-4 font-medium text-slate-800">{{ $colis->prix }} DH</td>
                            <td class="p-4 text-emerald-600 font-medium">40 DH</td>
                            <td class="p-4"><span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">Livré</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Aucun colis marqué comme "Livré" pour le moment. (Modifiez le statut d'un colis dans la base pour tester).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
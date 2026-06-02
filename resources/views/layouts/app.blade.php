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
        
        <div class="w-64 bg-slate-900 text-slate-300 flex flex-col shadow-xl">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-box-open text-indigo-500"></i> MonColis
                </h1>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                @if(request()->is('admin*'))
                    <a href="/admin/dashboard" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-chart-pie mr-2"></i>Dashboard</a>
                    <a href="/admin/colis" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-boxes mr-2"></i>Colis</a>
                    <a href="/admin/users" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-users mr-2"></i>Utilisateurs</a>
                    <a href="/admin/livreurs" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-truck mr-2"></i>Livreurs</a>
                    <a href="/admin/ecommercants" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-store mr-2"></i>E-commerçants</a>
                    <a href="/admin/affectations" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-route mr-2"></i>Affectations</a>
                    <a href="/admin/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-wallet mr-2"></i>Finances</a>
                    <a href="/admin/audit" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-history mr-2"></i>Audit Log</a>
                @else
                <!-- 📊 Mon Dashboard -->
<a href="/ecomercant/dashboard" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
    <i class="fas fa-chart-pie mr-2"></i> Mon Dashboard
</a>

<!-- 📦 Mes Colis -->
<a href="/ecomercant/colis" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
    <i class="fas fa-boxes mr-2"></i> Mes Colis
</a>

<!-- 💳 Mon Wallet -->
<a href="/ecomercant/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
    <i class="fas fa-wallet mr-2"></i> Mon Wallet
</a>

<!-- 💸 Demande Retrait -->
<a href="/ecomercant/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
    <i class="fas fa-hand-holding-usd mr-2"></i> Demande Retrait
</a>
                @endif
            </nav>
            <div class="p-4 border-t border-slate-800">
                <a href="/logout" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg font-medium transition">
                    <i class="fas fa-sign-out-alt w-5"></i> Déconnexion
                </a>
            </div>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
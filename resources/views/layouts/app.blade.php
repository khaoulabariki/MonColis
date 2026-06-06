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

    <div class="md:hidden bg-slate-900 text-white p-4 flex items-center justify-between shadow-md">
        <h1 class="text-lg font-bold flex items-center gap-2">
            <i class="fas fa-box-open text-indigo-500"></i> MonColis
        </h1>
        <button onclick="toggleMobileMenu()" class="text-white focus:outline-none p-2 cursor-pointer">
            <i class="fas fa-bars text-xl" id="menuIcon"></i>
        </button>
    </div>

    <div class="flex flex-col md:flex-row min-h-screen">
        
        <div id="sidebar" class="hidden md:flex w-full md:w-64 bg-slate-900 text-slate-300 flex-col shadow-xl transition-all duration-300">
            
            <div class="p-6 border-b border-slate-800 hidden md:block">
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-box-open text-indigo-500"></i> MonColis
                </h1>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
    
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-chart-pie mr-2"></i>Dashboard</a>
                    <a href="{{ route('admin.colis.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-boxes mr-2"></i>Colis</a>
                    <a href="{{ route('admin.administrateurs.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-user-shield mr-2"></i>Administrateurs</a>
                    <a href="{{ route('admin.livreurs.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-truck mr-2"></i>Livreurs</a>
                    <a href="{{ route('admin.ecommercants.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-store mr-2"></i>E-commerçants</a>
                    <a href="{{ route('admin.affectations.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-route mr-2"></i>Affectations</a>
                    <a href="{{ route('admin.finances.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-wallet mr-2"></i>Finances</a>
                    <a href="{{ route('admin.audit.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition"><i class="fas fa-history mr-2"></i>Audit Log</a>

                @elseif(auth()->user()->role === 'livreur')
                    <a href="{{ route('livreur.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-chart-pie mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('livreur.mes_livraisons') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-truck mr-2"></i> Mes Livraisons
                    </a>

                @else
                    <a href="/ecommercant/dashboard" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-chart-pie mr-2"></i> Mon Dashboard
                    </a>
                    <a href="/ecommercant/colis" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-boxes mr-2"></i> Mes Colis
                    </a>
                    <a href="/ecommercant/finances" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-wallet mr-2"></i> Mon Wallet
                    </a>
                    
                    <a href="/ecommercant/finances?action=retrait" 
                       onclick="if(window.location.pathname.includes('finances')) { event.preventDefault(); openRetraitModal(); } toggleMobileMenu();"
                       class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition cursor-pointer">
                        <i class="fas fa-hand-holding-usd mr-2"></i> Demande Retrait
                    </a>
                @endif

            </nav>
            
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg font-medium transition bg-transparent border-0 cursor-pointer text-left">
                        <i class="fas fa-sign-out-alt w-5"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 p-4 md:p-8 overflow-y-auto w-full">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const menuIcon = document.getElementById('menuIcon');
            
            // Appliquer les changements uniquement si l'affichage correspond à un mobile
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('hidden');
                
                // Permet de basculer l'icône entre le menu burger classique et la croix de fermeture (X)
                if (sidebar.classList.contains('hidden')) {
                    menuIcon.className = "fas fa-bars text-xl";
                } else {
                    menuIcon.className = "fas fa-times text-xl";
                }
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
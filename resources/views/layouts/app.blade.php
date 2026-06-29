<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipily - Administration</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .text-brand-blue { color: #0A4BB3; }
        .bg-brand-blue { background-color: #0A4BB3; }
        .text-brand-orange { color: #FF6B00; }
        .bg-brand-orange { background-color: #FF6B00; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800">

    <div class="md:hidden bg-white border-b border-slate-200 text-slate-800 p-4 flex items-center justify-between shadow-xs">
        <h1 class="text-lg font-black flex items-center gap-2 tracking-tight">
            <div class="w-6 h-6 bg-brand-blue rounded-md flex items-center justify-center">
                <i class="fas fa-arrow-up text-white text-[10px] font-black transform rotate-45" style="color: #FF6B00;"></i>
            </div>
            Ship<span class="text-brand-orange">ily</span>
        </h1>
        <button onclick="toggleMobileMenu()" class="text-slate-800 focus:outline-none p-2 cursor-pointer">
            <i class="fas fa-bars text-xl" id="menuIcon"></i>
        </button>
    </div>

    <div class="flex flex-col md:flex-row min-h-screen">
        
        <div id="sidebar" class="hidden md:flex w-full md:w-64 bg-white text-slate-500 flex-col border-r border-slate-200/80 transition-all duration-300">
            
            <div class="p-6 border-b border-slate-100 hidden md:block">
                <h1 class="text-xl font-black text-brand-blue flex items-center gap-2.5 tracking-tight">
                    <div class="w-8 h-8 bg-brand-blue rounded-lg flex items-center justify-center shadow-sm shadow-blue-900/10">
                        <i class="fas fa-arrow-up text-white text-sm font-black transform rotate-45" style="color: #FF6B00;"></i>
                    </div>
                    <span>Ship<span class="text-brand-orange">ily</span></span>
                </h1>
            </div>
            
            <nav class="flex-1 p-4 space-y-1">
    
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/dashboard') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-chart-pie w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.colis.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/colis*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-boxes w-5"></i> Colis
                    </a>
                    <a href="{{ route('admin.administrateurs.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/administrateurs*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-user-shield w-5"></i> Administrateurs
                    </a>
                    <a href="{{ route('admin.livreurs.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/livreurs*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-truck w-5"></i> Livreurs
                    </a>
                    <a href="{{ route('admin.ecommercants.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/ecommercants*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-store w-5"></i> E-commerçants
                    </a>
                    <a href="{{ route('admin.affectations.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/affectations*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-route w-5"></i> Affectations
                    </a>
                    <a href="{{ route('admin.finances.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/finances*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-wallet w-5"></i> Finances
                    </a>
                    <a href="{{ route('admin.audit.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('admin/audit*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-history w-5"></i> Audit Log
                    </a>

                @elseif(auth()->user()->role === 'livreur')
                    <a href="{{ route('livreur.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('livreur/dashboard*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-chart-pie w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('livreur.mes_livraisons') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('livreur/mes_livraisons*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-truck w-5"></i> Mes Livraisons
                    </a>

                @else
                    <a href="/ecommercant/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('ecommercant/dashboard*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-chart-pie w-5"></i> Mon Dashboard
                    </a>
                    <a href="/ecommercant/colis" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('ecommercant/colis*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-boxes w-5"></i> Mes Colis
                    </a>

                    <a href="/ecommercant/destinataires" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('ecommercant/destinataires*') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-users w-5"></i> Mes Destinataires
                    </a>

                    <a href="/ecommercant/finances" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ Request::is('ecommercant/finances*') && !request()->has('action') ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }}">
                        <i class="fas fa-wallet w-5"></i> Mon Wallet
                    </a>
                    
                    <a href="/ecommercant/finances?action=retrait" 
                       onclick="if(window.location.pathname.includes('finances')) { event.preventDefault(); openRetraitModal(); } toggleMobileMenu();"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold rounded-xl transition {{ request()->get('action') === 'retrait' ? 'bg-brand-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-blue' }} cursor-pointer">
                        <i class="fas fa-hand-holding-usd w-5"></i> Demande Retrait
                    </a>
                @endif

            </nav>
            
            <div class="p-4 border-t border-slate-100">
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-rose-500 hover:bg-rose-50 rounded-xl font-bold transition bg-transparent border-0 cursor-pointer text-left text-sm">
                        <i class="fas fa-sign-out-alt w-5"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 p-6 md:p-8 overflow-y-auto w-full">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const menuIcon = document.getElementById('menuIcon');
            
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('hidden');
                
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
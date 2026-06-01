<nav class="flex-1 p-4 space-y-2">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               onclick="window.location.href='{{ route('admin.dashboard') }}'; return true;"
               class="block px-4 py-2.5 rounded-lg font-medium transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                Dashboard
            </a>
            
            {{-- Colis (Db ghadi t-forca n9iya) --}}
            <a href="{{ route('admin.colis.index') }}" 
               onclick="window.location.href='{{ route('admin.colis.index') }}'; return true;"
               class="block px-4 py-2.5 rounded-lg font-medium transition duration-150 {{ request()->routeIs('admin.colis.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                Colis
            </a>
            
            {{-- Utilisateurs --}}
            <a href="{{ route('admin.users.index') }}" 
               onclick="window.location.href='{{ route('admin.users.index') }}'; return true;"
               class="block px-4 py-2.5 rounded-lg font-medium transition duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                Utilisateurs
            </a>
            
            <a href="#" class="block px-4 py-2.5 rounded-lg font-medium text-blue-100 hover:bg-blue-600 hover:text-white transition duration-150">
                Affectations
            </a>
            
            <a href="#" class="block px-4 py-2.5 rounded-lg font-medium text-blue-100 hover:bg-blue-600 hover:text-white transition duration-150">
                Finances
            </a>
            
            <a href="#" class="block px-4 py-2.5 rounded-lg font-medium text-blue-100 hover:bg-blue-600 hover:text-white transition duration-150">
                Audit Log
            </a>
        </nav>
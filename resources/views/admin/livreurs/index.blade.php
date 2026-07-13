@extends('layouts.app')

@section('content')
<div class="container-fluid my-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-2 font-medium">
            <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3 rounded-2xl mb-6 text-sm font-medium">
            <ul class="list-disc ps-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="addLivreurForm" class="hidden bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/50 p-8 mb-8 transition-all">
        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    <i class="fas fa-user-plus text-[#0A4BB3] me-2"></i>{{ __('Nouveau Livreur') }}
                </h3>
                <p class="text-xs font-medium text-slate-400 mt-0.5">{{ __('Créez un nouveau profil de livreur pour la plateforme.') }}</p>
            </div>
            <button onclick="toggleForm()" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center border-0 cursor-pointer text-lg transition">&times;</button>
        </div>
        
        <form action="{{ route('admin.livreurs.store') }}" method="POST" class="space-y-6 m-0 p-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('Nom') }}</label>
                    <input type="text" name="nom" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('Prénom') }}</label>
                    <input type="text" name="prenom" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('Adresse Email') }}</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-mono text-slate-800 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('Téléphone') }}</label>
                    <input type="text" name="telephone" class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ __('Mot de passe') }}</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-50">
                <button type="button" onclick="toggleForm()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl transition cursor-pointer">{{ __('Annuler') }}</button>
                <button type="submit" class="bg-[#0A4BB3] hover:bg-[#083da3] text-white font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl transition shadow-lg shadow-blue-900/10 cursor-pointer">{{ __('Enregistrer') }}</button>
            </div>
        </form>
    </div>
        
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-xs overflow-hidden">
        
        <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ __('Logistique & Distribution') }}</span>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Liste des Livreurs') }}</h2>
                <p class="text-slate-400 text-xs mt-0.5 font-medium">{{ __('Gérez les accès et les profils des livreurs de la plateforme.') }}</p>
            </div>
            
            <button onclick="toggleForm()" class="bg-[#0A4BB3] hover:bg-[#083da3] text-white font-black text-xs uppercase tracking-widest px-5 py-3 rounded-2xl transition shadow-lg shadow-blue-900/10 flex items-center gap-2.5 cursor-pointer">
                <i class="fas fa-user-plus text-[11px]"></i> {{ __('Ajouter un Livreur') }}
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6 text-start">ID</th>
                        <th class="py-5 px-6 text-start">{{ __('Nom & Prénom') }}</th>
                        <th class="py-5 px-6 text-start">{{ __('Adresse Email') }}</th>
                        <th class="py-5 px-6 text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($livreursList ?? [] as $livreur)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-5 px-6 font-mono font-bold text-slate-400 text-xs">#{{ $livreur->id }}</td>
                            <td class="py-5 px-6 font-bold text-slate-800 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0A4BB3] flex items-center justify-center text-xs">
                                    <i class="fas fa-truck"></i>
                                </div>
                                {{ $livreur->nom }} {{ $livreur->prenom }}
                            </td>
                            <td class="py-5 px-6 font-mono text-xs text-slate-500">{{ $livreur->email }}</td>
                            <td class="py-5 px-6 text-center">
                                <div class="flex justify-center items-center">
                                    <form action="{{ route('admin.livreurs.destroy', $livreur->id) }}" method="POST" onsubmit="return confirm(@json(__('Êtes-vous sûr de vouloir supprimer définitivement ce livreur ?')));" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition bg-transparent border-0 p-0 cursor-pointer align-middle text-base">
                                            <i class="fas fa-trash-alt" title="{{ __('Supprimer') }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-slate-400 font-bold">
                                <i class="fas fa-users-slash text-2xl block mb-2 text-slate-300"></i> {{ __('Aucun livreur trouvé dans la base de données.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('addLivreurForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection
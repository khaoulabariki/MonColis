@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl border border-slate-200/60 shadow-xl shadow-slate-100/50 p-8 my-6">
    
    <div class="mb-8">
        <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest block mb-1">Paramètres du compte</span>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">Mon Profil</h1>
        <p class="text-xs font-medium text-slate-400 mt-0.5">Gérez vos informations personnelles et sécurisez votre mot de passe.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200/60 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profil.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nom</label>
                <input type="text" name="nom" value="{{ $user->nom }}" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prénom</label>
                <input type="text" name="prenom" value="{{ $user->prenom }}" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Téléphone</label>
                <input type="text" name="telephone" value="{{ $user->telephone }}" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Adresse Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm font-medium text-slate-800 focus:outline-none transition">
            </div>
        </div>

        <hr class="border-slate-100 my-6">

        <div class="mb-4">
            <h3 class="text-sm font-black text-slate-800">Sécurité (Optionnel)</h3>
            <p class="text-xs text-slate-400 font-medium">Laissez vide si vous ne souhaitez pas modifier votre mot de passe.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nouveau mot de passe</label>
                <input type="password" name="password" class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm focus:outline-none transition">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-slate-200 focus:border-[#0A4BB3] rounded-xl text-sm focus:outline-none transition">
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-lg shadow-orange-500/10 cursor-pointer">
                Sauvegarder les changements
            </button>
        </div>
    </form>
</div>
@endsection
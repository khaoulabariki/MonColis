@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto my-4">
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mes Destinataires</h1>
            <p class="text-xs font-medium text-slate-400 mt-1">Gérez la liste de vos clients et destinataires enregistrés.</p>
        </div>
        <div>
            <button onclick="openDestinataireModal()" class="bg-brand-blue hover:bg-blue-800 text-white font-black px-5 py-3 rounded-xl text-xs tracking-wider uppercase transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fas fa-user-plus text-sm"></i> Ajouter un destinataire
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xs border border-slate-200/60 overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="p-4 pl-6">Nom Complet</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4">Ville</th>
                        <th class="p-4 pr-6">Adresse</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-bold text-xs">
                    @forelse($destinataires as $dest)
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="p-4 pl-6 text-slate-900 font-black">{{ $dest->prenom }} {{ $dest->nom }}</td>
                            <td class="p-4 text-brand-blue font-mono">{{ $dest->telephone }}</td>
                            <td class="p-4">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md uppercase tracking-wider text-[10px]">{{ $dest->ville }}</span>
                            </td>
                            <td class="p-4 pr-6 text-slate-500 font-medium max-w-xs truncate">{{ $dest->adresse }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-400 font-medium">
                                <div class="text-slate-200 text-2xl mb-1"><i class="fas fa-users-slash"></i></div>
                                Aucun destinataire enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="destinataireModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 w-full max-w-md p-6 transform scale-95 transition-transform duration-300 mx-4">
        
        <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider"><i class="fas fa-user-plus text-brand-blue mr-2"></i>Nouveau Destinataire</h3>
            <button onclick="closeDestinataireModal()" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
        </div>

        <form action="/ecommercant/destinataires/store" method="POST">
            @csrf
            
            <input type="hidden" name="utilisateur_id" value="{{ auth()->id() }}">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Nom</label>
                    <input type="text" name="nom" required placeholder=" " class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Prénom</label>
                    <input type="text" name="prenom" required placeholder=" " class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Téléphone</label>
                <input type="text" name="telephone" required placeholder=" " class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue">
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Ville</label>
                <input type="text" name="ville" required placeholder=" " class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue">
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Adresse Complète</label>
                <textarea name="adresse" rows="3" required placeholder="Adresse de livraison" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue resize-none"></textarea>
            </div>

            <div class="flex items-center gap-3 justify-end border-t border-slate-100 pt-4">
                <button type="button" onclick="closeDestinataireModal()" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 rounded-lg cursor-pointer">Annuler</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-black text-white bg-brand-blue hover:bg-blue-800 rounded-xl shadow-xs uppercase tracking-wider transition cursor-pointer">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDestinataireModal() {
        const modal = document.getElementById('destinataireModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeDestinataireModal() {
        const modal = document.getElementById('destinataireModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
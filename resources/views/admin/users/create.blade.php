@extends('layouts.admin')

@section('title', 'Ajouter un Utilisateur')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 text-xs font-semibold hover:underline">&larr; Retour à la liste</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Créer un Nouvel Utilisateur</h1>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Prénom</label>
                    <input type="text" name="prenom" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nom</label>
                    <input type="text" name="nom" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Adresse Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Téléphone</label>
                <input type="text" name="telephone" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Rôle dans le système</label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-blue-500">
                    <option value="client">E-commerçant (Client)</option>
                    <option value="livreur">Livreur</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Mot de passe</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="pt-4 border-t border-gray-50 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition">Annuler</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">Enregistrer</button>
            </div>
        </form>
    </div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">

        <!-- Titre + bouton de retour -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editer Admin</h1>
            <a href="{{ route('admins.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>

        <!-- Formulaire de mise a jour -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <form method="POST" action="{{ route('admins.update', $admin->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Infos lecture seule sur l'utilisateur -->
                    <section class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i> Infos user
                        </h2>

                        <!-- Grille responsive : une colonne sur mobile, deux sur medium+ -->
                        <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Nom</label>
                                <input type="text" value="{{ $admin->user->nom }}" class="w-full bg-gray-100 text-gray-600 border rounded-md px-3 py-2" readonly>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Prenom</label>
                                <input type="text" value="{{ $admin->user->prenom }}" class="w-full bg-gray-100 text-gray-600 border rounded-md px-3 py-2" readonly>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Email</label>
                                <input type="email" value="{{ $admin->user->email }}" class="w-full bg-gray-100 text-gray-600 border rounded-md px-3 py-2" readonly>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Role user</label>
                                <input type="text" value="{{ $admin->user->role->nom ?? 'N/A' }}" class="w-full bg-gray-100 text-gray-600 border rounded-md px-3 py-2" readonly>
                            </div>
                        </div>
                    </section>

                    <!-- Bloc editable : changer le role admin -->
                    <section class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i> Parametres Admin
                        </h3>

                        <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
                            <!-- Choix du type d'admin -->
                            <div>
                                <label for="role" class="block text-sm text-gray-700 mb-1">
                                    Type admin <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                                    <option value="">Choisir...</option>
                                    <option value="super admin" {{ old('role', $admin->role) === 'super admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Tu peux seulement changer le niveau ici</p>
                            </div>

                            <!-- Date creation -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Cree le</label>
                                <input type="text" value="{{ $admin->created_at->format('d/m/Y H:i') }}" class="w-full bg-gray-100 text-gray-600 border rounded-md px-3 py-2" readonly>
                            </div>
                        </div>
                    </section>

                    <!-- Info utile pour l'utilisateur -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div class="text-sm text-blue-700">
                                <strong>Note :</strong> Pour modifier le nom, le prenom, l'email ou le mot de passe, il faut aller dans la section "Users".
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admins.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 flex items-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 flex items-center space-x-2">
                            <i class="fas fa-save"></i>
                            <span>Mettre a jour le niveau</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

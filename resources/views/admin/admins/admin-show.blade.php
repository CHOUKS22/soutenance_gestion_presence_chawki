@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">
        <!-- Titre de la page et boutons principaux -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Details Admin</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admins.edit', $admin) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('admins.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>

        <!-- Bloc principal du profil admin -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-6 mb-6">
                    <!-- Si le user a une photo, on l'affiche, sinon on met une icone user -->
                    <div class="flex-shrink-0">
                        @if($admin->user->photo)
                            <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200"
                                 src="{{ asset('storage/' . $admin->user->photo) }}"
                                 alt="{{ $admin->user->nom }}">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center border-4 border-gray-200">
                                <i class="fas fa-user text-gray-500 text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Infos principales (nom, email, role) -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $admin->user->nom }} {{ $admin->user->prenom }}
                        </h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $admin->user->email }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-user-shield mr-2"></i>
                                <!-- On affiche le type d'admin en clair -->
                                @if($admin->role === 'super_admin')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Super Admin</span>
                                @elseif($admin->role === 'admin')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Admin</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Infos plus detaillees sur l'admin -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bloc infos perso -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Infos perso
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nom</label>
                                <p class="text-sm text-gray-900">{{ $admin->user->nom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Prenom</label>
                                <p class="text-sm text-gray-900">{{ $admin->user->prenom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Type admin</label>
                                <p class="text-sm text-gray-900">
                                    @if($admin->role === 'super_admin')
                                        Super Administrateur
                                    @elseif($admin->role === 'admin')
                                        Administrateur
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bloc infos contact -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-address-book mr-2"></i>
                            Contact
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-sm text-gray-900">{{ $admin->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Infos systeme (role, creation, update) -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-cog mr-2"></i>
                            Systeme
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Role systeme</label>
                                <p class="text-sm text-gray-900">{{ $admin->user->role->nom ?? 'Non defini' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Cree le</label>
                                <p class="text-sm text-gray-900">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Modifie le</label>
                                <p class="text-sm text-gray-900">{{ $admin->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bloc permissions (vide ici, a remplir si besoin plus tard) -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-key mr-2"></i>
                            Permissions
                        </h3>
                        <!-- Rien ici pour le moment -->
                    </div>
                </div>

                <!-- Boutons d'action en bas de page -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admins.edit', $admin) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Modifier</span>
                    </a>
                    <form method="POST" action="{{ route('admins.destroy', $admin) }}"
                          class="inline-block" onsubmit="return confirm('Confirmer suppression admin ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center space-x-2">
                            <i class="fas fa-trash"></i>
                            <span>Supprimer</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

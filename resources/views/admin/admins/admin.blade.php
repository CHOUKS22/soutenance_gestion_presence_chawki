@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-7xl mx-auto">
        <!-- Titre et bouton d'ajout -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Admins</h1>
            <button id="btnAdd" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Ajouter un Admin</span>
            </button>
        </div>

        <!-- Message flash si besoin -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <span>{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Fermer</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Formulaire d'ajout, cache par defaut -->
        <div id="formAdd" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
            <h3 class="text-xl font-semibold mb-4">Ajout infos Admin</h3>
            @if($usersAdmins->count() > 0)
                <form method="POST" action="{{ route('admins.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Choisir l'utilisateur</label>
                            <select id="user_id" name="user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">-- Choisir --</option>
                                @foreach($usersAdmins as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->nom }} {{ $user->prenom }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select id="role" name="role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">-- Choisir le role --</option>
                                <option value="super admin" {{ old('role') == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button type="button" id="btnCancel" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                    <p class="text-gray-600 mb-2">Aucun user admin dispo</p>
                    <p class="text-sm text-gray-500">Il faut d'abord creer des utilisateurs admin dans Users</p>
                    <a href="{{ route('admins.index') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Aller vers Users
                    </a>
                </div>
            @endif
        </div>

        <!-- Liste des admins -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-4">Liste des Admins</h3>
                @if($admins->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($admins as $admin)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="h-10 w-10 flex items-center">
                                                @if($admin->user->photo)
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                         src="{{ asset('storage/' . $admin->user->photo) }}"
                                                         alt="{{ $admin->user->nom }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-500"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $admin->user->nom }} {{ $admin->user->prenom }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $admin->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $admin->role == 'super admin' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ ucfirst($admin->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admins.show', $admin->id) }}"
                                                   class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admins.edit', $admin->id) }}"
                                                   class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-md">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admins.destroy', $admin->id) }}"
                                                      class="inline-block" onsubmit="return confirm('Confirmer suppression infos admin ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $admins->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucun admin trouve</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion affichage du formulaire d'ajout
    const btnAdd = document.getElementById('btnAdd');
    const formAdd = document.getElementById('formAdd');
    const btnCancel = document.getElementById('btnCancel');

    btnAdd.addEventListener('click', function() {
        formAdd.classList.remove('hidden');
        btnAdd.classList.add('hidden');
    });

    btnCancel.addEventListener('click', function() {
        formAdd.classList.add('hidden');
        btnAdd.classList.remove('hidden');
        // Reset le formulaire
        const form = formAdd.querySelector('form');
        if (form) form.reset();
    });

    // On permet de fermer les alertes vertes
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('svg');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                alert.remove();
            });
        }
    });
});
</script>
@endsection

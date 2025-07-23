@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Titre principal et bouton d'ajout -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
            <p class="text-gray-600">Créer et gérer les utilisateurs du système</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Ajouter
        </a>
    </div>

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-800">Liste des utilisateurs</h2>
            <div class="flex flex-col md:flex-row gap-4">
                <select id="roleFilter" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les rôles</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ strtolower($role->nom) }}">{{ ucfirst($role->nom) }}</option>
                    @endforeach
                </select>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="p-6" id="usersList">
            @forelse($users as $user)
                <div class="user-item flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition"
                     data-role="{{ strtolower($user->role->nom ?? '') }}"
                     data-name="{{ strtolower($user->nom . ' ' . $user->prenom) }}"
                     data-email="{{ strtolower($user->email) }}">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full overflow-hidden flex justify-center items-center mr-4">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover" alt="Photo">
                            @else
                                <span class="text-blue-600 font-semibold">{{ substr($user->nom,0,1) }}{{ substr($user->prenom,0,1) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $user->nom }} {{ $user->prenom }}</p>
                            <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">Ajouté le {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 text-sm rounded-full font-medium
                            {{ ($user->role->nom ?? '') === 'admin' ? 'bg-red-100 text-red-600' : '' }}
                            {{ ($user->role->nom ?? '') === 'etudiant' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ ($user->role->nom ?? '') === 'professeur' ? 'bg-green-100 text-green-600' : '' }}
                            {{ ($user->role->nom ?? '') === 'coordinateur' ? 'bg-purple-100 text-purple-600' : '' }}
                            {{ ($user->role->nom ?? '') === 'parent' ? 'bg-yellow-100 text-yellow-600' : '' }}
                            {{ !$user->role ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst($user->role->nom ?? 'Aucun rôle') }}
                        </span>
                        <a href="{{ route('users.show', $user) }}" title="Voir" class="text-gray-500 hover:text-blue-600 p-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user) }}" title="Modifier" class="text-gray-500 hover:text-green-600 p-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer {{ $user->nom }} {{ $user->prenom }} ?')" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-500 hover:text-red-600 p-2" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">Aucun utilisateur trouvé</p>
                    <p class="text-sm text-gray-400 mt-1">Ajoutez un utilisateur pour commencer</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Filtrer par rôle
document.getElementById('roleFilter')?.addEventListener('change', function () {
    const role = this.value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(item => {
        const itemRole = item.dataset.role;
        item.style.display = !role || itemRole === role ? 'flex' : 'none';
    });
});

// Filtrer par recherche
document.getElementById('searchInput')?.addEventListener('input', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(item => {
        const name = item.dataset.name;
        const email = item.dataset.email;
        item.style.display = name.includes(term) || email.includes(term) ? 'flex' : 'none';
    });
});
</script>
@endsection

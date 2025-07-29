@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">

        <!-- En-tete de la page avec le titre et le bouton pour ajouter un role -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Rôles</h1>
            <a href="{{ route('roles.create') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Nouveau Rôle</span>
            </a>
        </div>

        <!-- Quelques chiffres resumes sur les roles -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Nombre total de roles -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Rôles</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $roles->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-user-tag text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total des utilisateurs associes aux roles -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Utilisateurs</p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ $roles->sum(fn($role) => $role->users ? $role->users->count() : 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Role ayant le plus d'utilisateurs -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Rôle Principal</p>
                        <p class="text-sm font-bold text-purple-600">
                            {{ $roles->sortByDesc(fn($r) => $r->users?->count())->first()?->nom ?? 'Aucun' }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-crown text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Date du dernier role cree -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Dernière Création</p>
                        <p class="text-sm font-bold text-orange-600">
                            {{ $roles->sortByDesc('created_at')->first()?->created_at->format('d/m/Y') ?? 'Aucune' }}
                        </p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste complete des roles -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Liste des Rôles</h2>
            </div>

            @if($roles->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Utilisateurs</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($roles as $role)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                                <i class="fas fa-user-tag text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $role->nom }}</h3>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 text-sm">
                                        {{ Str::limit($role->description, 80) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ $role->users?->count() ?? 0 }} utilisateurs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $role->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <!-- Voir -->
                                            <a href="{{ route('roles.show', $role->id) }}"
                                               class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700"
                                               title="Voir les détails">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <!-- Modifier -->
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                               class="bg-yellow-600 text-white p-2 rounded-lg hover:bg-yellow-700"
                                               title="Modifier">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <!-- Supprimer -->
                                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-600 text-white p-2 rounded-lg hover:bg-red-700"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Si aucun role existe encore -->
                <div class="p-12 text-center">
                    <div class="p-4 bg-gray-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user-tag text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Aucun rôle</h4>
                    <p class="text-gray-500 mb-6">Commencez par créer votre premier rôle utilisateur.</p>
                    <a href="{{ route('roles.create') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 inline-flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Créer un Rôle</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

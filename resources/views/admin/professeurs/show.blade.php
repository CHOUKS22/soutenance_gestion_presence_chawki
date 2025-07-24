@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">

        {{-- en-tete --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Details du Professeur</h1>
            <div class="flex space-x-2">
                <a href="{{ route('professeurs.edit', $professeur) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('professeurs.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>

        {{-- profil professeur --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">

                {{-- photo et info de base --}}
                <div class="flex items-center space-x-6 mb-6">
                    {{-- photo --}}
                    <div class="flex-shrink-0">
                        @if($professeur->user->photo)
                            <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200"
                                 src="{{ asset('storage/' . $professeur->user->photo) }}"
                                 alt="{{ $professeur->user->nom }}">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center border-4 border-gray-200">
                                <i class="fas fa-user text-gray-500 text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- nom email role --}}
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $professeur->user->nom }} {{ $professeur->user->prenom }}
                        </h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-2"></i>{{ $professeur->user->email }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>Professeur
                            </span>
                        </div>
                    </div>
                </div>

                {{-- infos detaillees --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- infos personnelles --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>Informations personnelles
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nom</label>
                                <p class="text-sm text-gray-900">{{ $professeur->user->nom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Prenom</label>
                                <p class="text-sm text-gray-900">{{ $professeur->user->prenom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Filiere</label>
                                <p class="text-sm text-gray-900">{{ $professeur->filliere->nom }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- infos contact --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-address-book mr-2"></i>Informations de contact
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-sm text-gray-900">{{ $professeur->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- infos systeme --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-cog mr-2"></i>Informations systeme
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Role</label>
                                <p class="text-sm text-gray-900">{{ $professeur->user->role->nom ?? 'Non defini' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Cree le</label>
                                <p class="text-sm text-gray-900">{{ $professeur->created_at->format('d/m/Y a H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Modifie le</label>
                                <p class="text-sm text-gray-900">{{ $professeur->updated_at->format('d/m/Y a H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- stats --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>Statistiques
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-medium text-gray-600">Compte cree depuis</label>
                                <p class="text-sm text-gray-900">{{ $professeur->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- boutons actions --}}
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('professeurs.edit', $professeur) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 flex items-center space-x-2">
                        <i class="fas fa-edit"></i><span>Modifier</span>
                    </a>
                    <form method="POST" action="{{ route('professeurs.destroy', $professeur) }}"
                          class="inline-block" onsubmit="return confirm('Etes-vous sur de vouloir supprimer ce professeur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center space-x-2">
                            <i class="fas fa-trash"></i><span>Supprimer</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

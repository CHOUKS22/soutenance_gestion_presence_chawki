@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-7xl mx-auto">
        <!-- Titre principal -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Étudiants</h1>
            <a href="{{ route('etudiants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Ajouter Infos Étudiant</span>
            </a>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 border border-green-400 rounded px-4 py-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 border border-red-400 rounded px-4 py-3 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tableau des étudiants -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Liste des Étudiants</h2>

                @if ($etudiants->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-6 py-3 text-left">Photo</th>
                                    <th class="px-6 py-3 text-left">Nom Complet</th>
                                    <th class="px-6 py-3 text-left">Email</th>
                                    <th class="px-6 py-3 text-left">Date de naissance</th>
                                    <th class="px-6 py-3 text-left">Téléphone</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($etudiants as $etudiant)
                                    <tr>
                                        <!-- Photo -->
                                        <td class="px-6 py-4">
                                            @if ($etudiant->user->photo)
                                                <img src="{{ asset('storage/' . $etudiant->user->photo) }}" alt="Photo" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500"></i>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Nom -->
                                        <td class="px-6 py-4">
                                            {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                                        </td>

                                        <!-- Email -->
                                        <td class="px-6 py-4">
                                            {{ $etudiant->user->email }}
                                        </td>

                                        <!-- Date de naissance -->
                                        <td class="px-6 py-4">
                                            {{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : 'Non défini' }}
                                        </td>

                                        <!-- Téléphone -->
                                        <td class="px-6 py-4">
                                            {{ $etudiant->telephone ?: 'Non défini' }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('etudiants.show', $etudiant) }}" class="text-blue-600 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('etudiants.edit', $etudiant) }}" class="text-yellow-600 bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('etudiants.destroy', $etudiant) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 bg-red-100 hover:bg-red-200 px-3 py-1 rounded">
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

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $etudiants->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucun étudiant trouvé</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

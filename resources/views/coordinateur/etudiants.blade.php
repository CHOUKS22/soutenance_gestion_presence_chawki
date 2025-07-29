@extends('layouts.coordinateur')

@section('title', 'Mes Étudiants')
@section('subtitle', 'Liste des étudiants inscrits dans vos classes')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Étudiants</h1>
                <p class="mt-1 text-gray-600">Liste des étudiants pour chaque classe</p>
            </div>
        </div>

        @forelse ($anneesClasses as $ac)
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                    Classe : {{ $ac->classe->nom }} ({{ $ac->anneeAcademique->libelle }})
                </h2>

                @if ($ac->etudiants->count())
                    <ul class="list-disc pl-6 text-gray-700">
                        @foreach ($ac->etudiants as $etudiant)
                            <li>{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 italic">Aucun étudiant inscrit dans cette classe.</p>
                @endif
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded shadow">
                <i class="fas fa-users-slash text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700">Aucune classe trouvée</h3>
                <p class="text-gray-500 mt-2">Vous n'avez pas encore de classes associées.</p>
            </div>
        @endforelse
    </div>
@endsection

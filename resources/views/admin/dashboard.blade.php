@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
                <p class="text-gray-600 mt-1">Bienvenue dans l'interface d'administration IFRAN</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Connecté en tant que</p>
                <p class="font-medium text-gray-800">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['icon' => 'fa-users', 'color' => 'blue', 'label' => 'Total Utilisateurs', 'count' => $totalsUsers ?? 0],
                ['icon' => 'fa-user-graduate', 'color' => 'green', 'label' => 'Étudiants', 'count' => $totalsEtudiants ?? 0],
                ['icon' => 'fa-chalkboard-teacher', 'color' => 'purple', 'label' => 'Professeurs', 'count' => $totalsProfesseurs ?? 0],
                ['icon' => 'fa-user-friends', 'color' => 'orange', 'label' => 'Parents', 'count' => $totalsParents ?? 0],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-{{ $stat['color'] }}-100 rounded-full">
                        <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stat['count'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('etudiants.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="p-3 bg-blue-100 rounded-lg mr-3">
                    <i class="fas fa-user-plus text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Nouvel étudiant</p>
                    <p class="text-sm text-gray-500">Ajouter un étudiant</p>
                </div>
            </a>

            <a href="{{ route('professeurs.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="p-3 bg-green-100 rounded-lg mr-3">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Nouveau professeur</p>
                    <p class="text-sm text-gray-500">Ajouter un professeur</p>
                </div>
            </a>

            <a href="{{ route('classes.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="p-3 bg-purple-100 rounded-lg mr-3">
                    <i class="fas fa-school text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Nouvelle classe</p>
                    <p class="text-sm text-gray-500">Créer une classe</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-10">

    <!-- Hero / Bienvenue -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl p-8 shadow-lg flex flex-col md:flex-row justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Bienvenue, {{ auth()->user()->nom }} {{ auth()->user()->prenom }}</h1>
            <p class="text-white/90">Vous êtes connecté sur l’interface administrateur de la plateforme IFRAN.</p>
        </div>
        <div class="mt-6 md:mt-0">
            <i class="fas fa-user-shield text-6xl opacity-70"></i>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['icon' => 'fa-users', 'color' => 'bg-indigo-500', 'label' => 'Total Utilisateurs', 'count' => $totalsUsers ?? 0],
                ['icon' => 'fa-user-graduate', 'color' => 'bg-green-500', 'label' => 'Étudiants', 'count' => $totalsEtudiants ?? 0],
                ['icon' => 'fa-chalkboard-teacher', 'color' => 'bg-purple-500', 'label' => 'Professeurs', 'count' => $totalsProfesseurs ?? 0],
                ['icon' => 'fa-user-friends', 'color' => 'bg-yellow-500', 'label' => 'Parents', 'count' => $totalsParents ?? 0],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center text-center hover:shadow-md transition">
                <div class="w-16 h-16 flex items-center justify-center rounded-full {{ $stat['color'] }} text-white text-2xl shadow mb-4">
                    <i class="fas {{ $stat['icon'] }}"></i>
                </div>
                <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stat['count'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-2xl shadow p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Actions rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <a href="{{ route('etudiants.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-center transition group">
                <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-2xl mb-4">
                    <i class="fas fa-user-plus"></i>
                </div>
                <p class="font-semibold text-gray-800">Nouvel étudiant</p>
                <span class="text-sm text-gray-500 group-hover:underline">Ajouter un étudiant</span>
            </a>

            <a href="{{ route('professeurs.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-center transition group">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-4">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <p class="font-semibold text-gray-800">Nouveau professeur</p>
                <span class="text-sm text-gray-500 group-hover:underline">Ajouter un professeur</span>
            </a>

            <a href="{{ route('classes.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-center transition group">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-2xl mb-4">
                    <i class="fas fa-school"></i>
                </div>
                <p class="font-semibold text-gray-800">Nouvelle classe</p>
                <span class="text-sm text-gray-500 group-hover:underline">Créer une classe</span>
            </a>

        </div>
    </div>
</div>
@endsection

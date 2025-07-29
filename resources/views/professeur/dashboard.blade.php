@extends('layouts.professeur')

@section('title', 'Tableau de bord Professeur')
@section('subtitle', 'Vue d\'ensemble des activités et statistiques')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Affichage Profil -->
    <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center justify-center">
        <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mb-4">
            @if (auth()->user()->photo)
                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo de profil" class="w-full h-full object-cover rounded-full">
            @else
                <i class="fas fa-user text-white text-2xl"></i>
            @endif
        </div>
        <h2 class="text-xl font-bold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</h2>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
    </div>

    <!-- Statistique: Total Seances -->
    <div class="bg-blue-100 p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Séances totales</h3>
        <p class="text-3xl font-bold text-blue-700">{{ $totalSeances }}</p>
        <p class="text-sm text-blue-600 mt-2">Depuis le début du semestre</p>
    </div>

    <!-- Statistique: Seances à venir -->
    <div class="bg-yellow-100 p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Séances à venir</h3>
        <p class="text-3xl font-bold text-yellow-700">{{ $seancesAVenir }}</p>
        <p class="text-sm text-yellow-600 mt-2">Cette semaine</p>
    </div>

    <!-- Statistique: Absences non justifiees -->
    <div class="bg-red-100 p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-red-800 mb-2">Absences non justifiées</h3>
        <p class="text-3xl font-bold text-red-700">{{ $absencesNonJustifiees }}</p>
        <a href="{{ route('absences.non_justifiees') }}" class="mt-4 block text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded">Voir les absences</a>
    </div>
</div>

<!-- Deuxieme section : Liens et Rappels -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Raccourcis utiles</h3>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('professeur.emploi_du_temps') }}" class="flex items-center text-blue-600 hover:underline">
                    <i class="fas fa-clock mr-2"></i> Emploi du temps
                </a>
            </li>
            <li>
                <a href="{{ route('professeur.seances.index') }}" class="flex items-center text-blue-600 hover:underline">
                    <i class="fas fa-calendar-alt mr-2"></i> Gérer mes séances
                </a>
            </li>
        </ul>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Dernières notifications</h3>
        <ul class="text-sm text-gray-700 space-y-2">
            <li> {{ $absencesNonJustifiees }} absences non justifiées en attente</li>
            <li> Prochaine séance prévue cette semaine : {{ $seancesAVenir }}</li>
        </ul>
    </div>
</div>

<!-- Troisieme section : Graphique ou resume -->
<div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Récapitulatif rapide</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $tauxPresence }}%</p>
            <p class="text-sm text-gray-500">Présences moyennes</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $moyenneParSemaine }}</p>
            <p class="text-sm text-gray-500">Séances par semaine</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $dureeMoyenne }}</p>
            <p class="text-sm text-gray-500">Durée moyenne de cours</p>
        </div>
    </div>
</div>
@endsection

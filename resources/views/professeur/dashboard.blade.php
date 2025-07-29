@extends('layouts.professeur')

@section('title', 'Tableau de bord Professeur')
@section('subtitle', 'Vue d\'ensemble des activités et statistiques')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Affichage Profil -->
        <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mb-4">
                @if (auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo de profil"
                        class="w-full h-full object-cover rounded-full">
                @else
                    <i class="fas fa-user text-white text-2xl"></i>
                @endif
            </div>
            <h1 class="text-xl font-bold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</h1>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>

        <!-- Statistique: Total Seances -->
        <div class="bg-blue-100 p-6 rounded-xl shadow">
            <p class="text-lg font-semibold text-blue-800 mb-2">Séances totales</p>
            <p class="text-3xl font-bold text-blue-700">{{ $totalSeances }}</p>
            <p class="text-sm text-blue-600 mt-2">Depuis le début du semestre</p>
        </div>

        <!-- Statistique: Seances à venir -->
        <div class="bg-yellow-100 p-6 rounded-xl shadow">
            <p class="text-lg font-semibold text-yellow-800 mb-2">Séances à venir</p>
            <p class="text-3xl font-bold text-yellow-700">{{ $seancesAVenir }}</p>
            <p class="text-sm text-yellow-600 mt-2">Cette semaine</p>
        </div>

        <!-- Statistique: Absences non justifiees -->
        <div class="bg-red-100 p-6 rounded-xl shadow">
            <p class="text-lg font-semibold text-red-800 mb-2">Absences non justifiées</p>
            <p class="text-3xl font-bold text-red-700">{{ $absencesNonJustifiees }}</p>
            <a href="{{ route('absences.non_justifiees') }}"
                class="mt-4 block text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded">Voir les absences</a>
        </div>
    </div>

    <!-- Deuxieme section : Liens et Rappels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-lg font-bold text-gray-800 mb-4">Raccourcis utiles</p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('professeur.emploi_du_temps') }}"
                        class="flex items-center text-blue-600 hover:underline">
                        <i class="fas fa-clock mr-2"></i> Emploi du temps
                    </a>
                </li>
                <li>
                    <a href="{{ route('professeur.seances.index') }}"
                        class="flex items-center text-blue-600 hover:underline">
                        <i class="fas fa-calendar-alt mr-2"></i> Gérer mes séances
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border">
            <p class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-bell text-yellow-500"></i>
                Dernières notifications
            </p>

            <ul class="space-y-4">
                <li class="flex items-start bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="p-2 bg-red-100 text-red-600 rounded-full">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-4 text-sm text-red-800">
                        {{ $absencesNonJustifiees }} absence{{ $absencesNonJustifiees > 1 ? 's' : '' }} non
                        justifiée{{ $absencesNonJustifiees > 1 ? 's' : '' }} en attente
                    </div>
                </li>

                <li class="flex items-start bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-full">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="ml-4 text-sm text-blue-800">
                        Prochaine séance prévue cette semaine : {{ $seancesAVenir }}
                    </div>
                </li>
            </ul>
        </div>

    </div>

    <!-- Troisieme section : Graphique ou resume -->
    <div class="bg-white p-6 rounded-xl shadow-md border">
        <p class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-chart-pie text-indigo-500"></i>
            Récapitulatif rapide
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Présences moyennes --}}
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 text-center shadow-sm">
                <div class="flex justify-center mb-3">
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-full">
                        <i class="fas fa-user-check text-lg"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-indigo-800">{{ $tauxPresence }}%</p>
                <p class="text-sm text-indigo-700 mt-1">Présences moyennes</p>
            </div>

            {{-- Séances par semaine --}}
            <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center shadow-sm">
                <div class="flex justify-center mb-3">
                    <div class="p-3 bg-green-100 text-green-600 rounded-full">
                        <i class="fas fa-calendar-week text-lg"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-green-800">{{ $moyenneParSemaine }}</p>
                <p class="text-sm text-green-700 mt-1">Séances par semaine</p>
            </div>

            {{-- Durée moyenne --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 text-center shadow-sm">
                <div class="flex justify-center mb-3">
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-yellow-800">{{ $dureeMoyenne }}</p>
                <p class="text-sm text-yellow-700 mt-1">Durée moyenne de cours</p>
            </div>
        </div>
    </div>
@endsection

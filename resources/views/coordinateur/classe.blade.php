@extends('layouts.coordinateur')

@section('title', 'Mes Classes')
@section('subtitle', 'Liste des classes associées')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Mes Classes</h1>

    <!-- Filtres de selection -->
    <form method="GET" class="flex flex-wrap items-end gap-6 mb-10">
        <div class="w-64">
            <label for="annee_id" class="block text-sm font-semibold text-gray-700 mb-1">Année Académique</label>
            <select name="annee_id" id="annee_id" onchange="this.form.submit()"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Choisir une année --</option>
                @foreach ($annees as $id => $classes)
                    <option value="{{ $id }}" {{ $anneeId == $id ? 'selected' : '' }}>
                        {{ $classes->first()->anneeAcademique->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        @if ($anneeId)
            <div class="w-64">
                <label for="classe_id" class="block text-sm font-semibold text-gray-700 mb-1">Classe</label>
                <select name="classe_id" id="classe_id" onchange="this.form.submit()"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir une classe --</option>
                    @foreach ($annees[$anneeId] as $classe)
                        <option value="{{ $classe->classe->id }}" {{ $classeId == $classe->classe->id ? 'selected' : '' }}>
                            {{ $classe->classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </form>

    <!-- Affichage de la classe selectionnee -->
    @if ($selectedClasse)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-blue-800 mb-2">Classe : {{ $selectedClasse->classe->nom }}</h2>
            <p class="text-sm text-gray-600 mb-4">Année : {{ $selectedClasse->anneeAcademique->libelle }} | Ajoutée le {{ $selectedClasse->created_at->format('d/m/Y') }}</p>

            <!-- Etudiants -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Étudiants inscrits :</h3>
                @php $etudiants = $selectedClasse->etudiants ?? collect(); @endphp

                @if ($etudiants->count())
                    <ul class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($etudiants as $etudiant)
                            <li class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="text-gray-900 font-semibold">{{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}</div>
                                <div class="text-gray-500 text-sm">Email : {{ $etudiant->user->email }}</div>
                                <div class="text-gray-400 text-xs mt-1">ID : {{ $etudiant->id }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 italic">Aucun étudiant inscrit dans cette classe.</p>
                @endif
            </div>
        </div>
    @elseif ($anneeId && $classeId)
        <div class="text-red-500 font-semibold mt-6">Aucune classe ne vous est attribué pour l'instant pour cette année.</div>
    @endif
</div>
@endsection

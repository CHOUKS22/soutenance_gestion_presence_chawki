@extends('layouts.parent')

@section('title', 'Suivi de mes enfants')

@section('content')
<div class="max-w-7xl mx-auto px-4 mt-10 space-y-10">

    <!-- Sélection des enfants -->
    @if ($etudiants->count())
        <div class="bg-white p-6 rounded-xl shadow-md border">
            <h1 class="text-lg font-semibold text-gray-700 mb-4">Sélectionner un enfant :</h1>
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($etudiants as $etudiant)
                    <label class="relative block cursor-pointer border rounded-lg shadow-sm hover:shadow-lg transition p-4 bg-gray-50 group {{ request('etudiant_id') == $etudiant->id ? 'ring-2 ring-blue-500' : '' }}">
                        <input type="radio" name="etudiant_id" value="{{ $etudiant->id }}" onchange="this.form.submit()" class="absolute top-2 right-2 h-4 w-4 text-blue-600">
                        <div class="flex items-center space-x-4">
                            @if ($etudiant->user->photo)
                                <img src="{{ asset('storage/' . $etudiant->user->photo) }}" class="w-14 h-14 rounded-full object-cover border" alt="Avatar">
                            @else
                                <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center text-lg font-semibold">
                                    {{ strtoupper(substr($etudiant->user->prenom, 0, 1) . substr($etudiant->user->nom, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-md font-bold text-gray-800">{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}</p>
                            </div>
                        </div>
                    </label>
                @endforeach
            </form>
        </div>
    @endif

    @if ($etudiantSelectionne)
        <!-- Sélecteur de période -->
        <div class="bg-white p-4 rounded-md shadow-md border">
            <form method="GET" class="flex flex-wrap gap-4 items-center">
                <input type="hidden" name="etudiant_id" value="{{ $etudiantSelectionne->id }}">
                <label class="text-sm font-medium text-gray-700">Filtrer la période du taux de présence :</label>
                <select name="periode" onchange="this.form.submit()" class="border rounded px-3 py-2">
                    <option value="semaine" {{ $periode == 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="mois" {{ $periode == 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="annee" {{ $periode == 'annee' ? 'selected' : '' }}>Cette année</option>
                </select>
            </form>
        </div>

        <!-- Taux de présence -->
        <div class="bg-blue-100 p-6 rounded-lg shadow border">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Taux de présence – {{ ucfirst($periode) }}</h2>
            @if ($total > 0)
                <div class="relative w-full bg-gray-300 h-6 rounded-full">
                    <div class="absolute top-0 left-0 h-6 bg-blue-600 text-xs text-white text-center leading-6 rounded-full"
                         style="width: {{ $taux }}%;">
                        {{ $taux }} %
                    </div>
                </div>
            @else
                <p class="text-gray-500">Aucune donnée disponible.</p>
            @endif
        </div>

        <!-- Grille de synthèse -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

            <!-- Présences -->
            <div class="bg-green-100 p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-green-800 mb-4">Présences</h3>
                @forelse($presences as $presence)
                    <div class="mb-2 p-3 bg-white rounded shadow-sm border">
                        <div class="font-semibold">{{ $presence->seance->matiere->nom }}</div>
                        <div class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($presence->seance->date_debut)->format('d/m/Y H:i') }} – {{ $presence->seance->typeSeance?->nom ?? 'Type ?' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Aucune présence.</p>
                @endforelse
            </div>

            <!-- Retards -->
            <div class="bg-orange-100 p-6 rounded-xl shadow-md">
                <p class="text-lg font-bold text-orange-800 mb-4">Retards</p>
                @forelse($retards as $retard)
                    <div class="mb-2 p-3 bg-white rounded shadow-sm border">
                        <div class="font-semibold">{{ $retard->seance->matiere->nom }}</div>
                        <div class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($retard->seance->date_debut)->format('d/m/Y H:i') }} – {{ $retard->seance->typeSeance?->nom ?? 'Type ?' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Aucun retard.</p>
                @endforelse
            </div>

            <!-- Absences Non Justifiées -->
            <div class="bg-red-100 p-6 rounded-xl shadow-md">
                <p class="text-lg font-bold text-red-800 mb-4">Absences Non Justifiées</p>
                @forelse($absencesNonJustifiees as $absence)
                    <div class="mb-2 p-3 bg-white rounded shadow-sm border">
                        <div class="font-semibold">{{ $absence->seance->matiere->nom }}</div>
                        <div class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($absence->seance->date_debut)->format('d/m/Y H:i') }} – {{ $absence->seance->typeSeance?->nom ?? 'Type ?' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Aucune absence non justifiée.</p>
                @endforelse
            </div>

            <!-- Absences Justifiées -->
            <div class="bg-yellow-100 p-6 rounded-xl shadow-md">
                <p class="text-lg font-bold text-yellow-800 mb-4">Absences Justifiées</p>
                @forelse($absencesJustifiees as $absence)
                    <div class="mb-2 p-3 bg-white rounded shadow-sm border">
                        <div class="font-semibold">{{ $absence->seance->matiere->nom }}</div>
                        <div class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($absence->seance->date_debut)->format('d/m/Y H:i') }} – {{ $absence->seance->typeSeance?->nom ?? 'Type ?' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Aucune absence justifiée.</p>
                @endforelse
            </div>
        </div>

        <!-- Emploi du temps avec filtre par semaine -->
        <div class="bg-white p-6 rounded-lg shadow-md border">
            <p class="text-xl font-semibold text-gray-800 mb-4">Emploi du Temps</p>

            <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
                <input type="hidden" name="etudiant_id" value="{{ $etudiantSelectionne->id }}">
                <label for="semaine" class="text-sm font-medium text-gray-700">Filtrer par semaine :</label>
                <input type="week" name="semaine" id="semaine" class="border px-3 py-2 rounded" onchange="this.form.submit()" value="{{ request('semaine') }}">
            </form>

            @if ($seances->isEmpty())
                <p class="text-gray-500">Aucune séance prévue.</p>
            @else
                <table class="w-full table-auto border-collapse text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Jour</th>
                            <th class="px-3 py-2 text-left">Heure</th>
                            <th class="px-3 py-2 text-left">Matière</th>
                            <th class="px-3 py-2 text-left">Classe</th>
                            <th class="px-3 py-2 text-left">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($seances as $seance)
                            <tr>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($seance->date_debut)->translatedFormat('l d/m') }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}</td>
                                <td class="px-3 py-2">{{ $seance->matiere->nom }}</td>
                                <td class="px-3 py-2">{{ $seance->anneeClasse->classe->nom ?? '---' }}</td>
                                <td class="px-3 py-2">{{ $seance->typeSeance?->nom ?? '---' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
</div>
@endsection

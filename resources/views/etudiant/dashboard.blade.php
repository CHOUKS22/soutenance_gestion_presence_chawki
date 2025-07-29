@extends('layouts.etudiant')

@section('title', 'Tableau de bord')
@section('subtitle', 'Bienvenue, ' . auth()->user()->prenom)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Colonne gauche : Profil + stats --}}
    <div class="col-span-1 space-y-6">
        {{-- Profil --}}
        <div class="bg-white p-6 rounded-2xl shadow text-center">
            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-gray-200 mb-4">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo" class="object-cover w-full h-full">
                @else
                    <i class="fas fa-user text-4xl text-gray-400 flex items-center justify-center h-full w-full"></i>
                @endif
            </div>
            <h2 class="text-lg font-semibold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h2>
            <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
        </div>

        {{-- Statistiques --}}
        <div class="bg-white p-4 rounded-2xl shadow space-y-4 text-sm text-gray-700">
            <div class="flex justify-between">
                <span class="font-medium">Classe</span>
                <span class="text-blue-700 font-semibold">
                    @foreach($etudiant->anneeClasses as $ac)
                        {{ $ac->classe->nom ?? 'Non assignée' }}
                    @endforeach
                </span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Matières</span>
                <span class="text-green-700 font-semibold">{{ $etudiant->matieres_count ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Note d’assiduité</span>
                <span class="text-yellow-700 font-semibold">{{ $noteAssiduite !== null ? $noteAssiduite : '—' }} / 20</span>
            </div>
        </div>
    </div>

    {{-- Colonne droite : contenu principal --}}
    <div class="col-span-1 lg:col-span-3 space-y-8">

        {{-- Emploi du temps --}}
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">Emploi du temps</h3>
                <form method="GET" action="{{ route('etudiant.dashboard') }}" class="flex items-center space-x-2">
                    <label for="semaine" class="text-sm text-gray-600">Semaine :</label>
                    <input type="week" name="semaine" id="semaine" value="{{ request('semaine', now()->format('Y-\WW')) }}"
                        class="border rounded px-2 py-1 text-sm">
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Voir</button>
                </form>
            </div>

            @if($seances->isEmpty())
                <p class="text-gray-500 text-sm text-center">Aucune séance prévue pour cette semaine.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto text-sm border rounded-xl overflow-hidden">
                        <thead class="bg-gray-100 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-2 text-left">Jour</th>
                                <th class="px-4 py-2 text-left">Heure</th>
                                <th class="px-4 py-2 text-left">Matière</th>
                                <th class="px-4 py-2 text-left">Professeur</th>
                                <th class="px-4 py-2 text-left">Type</th>
                                <th class="px-4 py-2 text-left">Classe</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($seances as $seance)
                                <tr>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($seance->date_debut)->translatedFormat('l d/m') }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-2">{{ $seance->matiere->nom ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ $seance->professeur->user->nom ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ $seance->typeSeance->nom ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ $seance->anneeClasse->classe->nom ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Présences --}}
        <div class="bg-green-50 border-l-4 border-green-400 p-5 rounded-xl shadow">
            <h3 class="text-lg font-bold text-green-800 mb-3"><i class="fas fa-check-circle mr-2"></i> Présences</h3>
            @if($presences->isEmpty())
                <p class="text-sm text-gray-600">Aucune présence enregistrée.</p>
            @else
                <ul class="list-disc ml-5 text-sm text-green-800 space-y-1">
                    @foreach($presences as $presence)
                        <li>{{ $presence->seance->matiere->nom ?? 'Matière' }} – {{ \Carbon\Carbon::parse($presence->seance->date_debut)->translatedFormat('l d M') }} ({{ $presence->statutPresence->libelle ?? 'Présent' }})</li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Absences justifiées et non justifiées --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Justifiées --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-5 rounded-xl shadow">
                <h3 class="text-lg font-bold text-blue-800 mb-3"><i class="fas fa-file-alt mr-2"></i> Absences justifiées</h3>
                @if($absencesJustifiees->isEmpty())
                    <p class="text-sm text-gray-600">Aucune absence justifiée.</p>
                @else
                    <ul class="list-disc ml-5 text-sm text-blue-800 space-y-1">
                        @foreach($absencesJustifiees as $absence)
                            <li>{{ $absence->seance->matiere->nom ?? 'Matière' }} – {{ \Carbon\Carbon::parse($absence->seance->date_debut)->translatedFormat('l d M') }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Non justifiées --}}
            <div class="bg-red-50 border-l-4 border-red-400 p-5 rounded-xl shadow">
                <h3 class="text-lg font-bold text-red-800 mb-3"><i class="fas fa-times-circle mr-2"></i> Absences non justifiées</h3>
                @if($absencesNonJustifiees->isEmpty())
                    <p class="text-sm text-gray-600">Aucune absence non justifiée.</p>
                @else
                    <ul class="list-disc ml-5 text-sm text-red-800 space-y-1">
                        @foreach($absencesNonJustifiees as $absence)
                            <li>{{ $absence->seance->matiere->nom ?? 'Matière' }} – {{ \Carbon\Carbon::parse($absence->seance->date_debut)->translatedFormat('l d M') }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

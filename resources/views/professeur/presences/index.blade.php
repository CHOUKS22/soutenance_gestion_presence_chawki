@extends('layouts.professeur')

@section('title', 'Gestion des présences')
@section('subtitle', 'Marquez ou mettez à jour les présences des étudiants pour cette séance')

@section('content')
<div class="space-y-6">
    <!-- Informations de la séance -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            Séance du {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y H:i') }}
        </h2>
        <p><strong>Classe :</strong> {{ $seance->classe->nom ?? 'N/A' }}</p>
        <p><strong>Matière :</strong> {{ $seance->matiere->nom ?? 'N/A' }}</p>
    </div>

    <!-- Table des étudiants -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Liste des étudiants</h3>

        @if ($etudiants->count())
            <table class="w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-2">Nom</th>
                        <th class="text-left px-4 py-2">Statut</th>
                        <th class="text-center px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($etudiants as $etudiant)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $etudiant->user->prenom ?? '' }} {{ $etudiant->user->nom ?? '' }}
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $presence = $presencesMarquees->get($etudiant->id);
                                    $absence = $absencesMarquees->get($etudiant->id);
                                    $statut = $presence?->statutPresence?->libelle ?? ($absence ? 'Absent' : 'Non marqué');
                                @endphp
                                <span class="px-2 py-1 text-sm rounded-full
                                    {{ $statut === 'Présent' ? 'bg-green-100 text-green-800' :
                                       ($statut === 'En retard' ? 'bg-yellow-100 text-yellow-800' :
                                       ($statut === 'Absent' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $statut }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <form method="POST" action="{{ route('professeur.presences.store', $seance) }}">
                                    @csrf
                                    <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                                    <input type="hidden" name="seance_id" value="{{ $seance->id }}">

                                    <select name="statuts_presence_id" class="border rounded px-2 py-1 text-sm">
                                        @foreach ($statutsPresence as $statut)
                                            <option value="{{ $statut->id }}">{{ $statut->libelle }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit"
                                        class="ml-2 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                        Valider
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Aucun étudiant trouvé pour cette classe.</p>
        @endif
    </div>
</div>
@endsection

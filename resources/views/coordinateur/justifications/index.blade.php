@extends('layouts.coordinateur')

@section('title', 'Justifications à faire')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 space-y-10">
    <h1 class="text-3xl font-bold text-gray-800">Absences à Justifier</h1>

    {{-- Absences à justifier --}}
    @if($absencesNonJustifiees->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow">
            Aucune absence à justifier pour le moment.
        </div>
    @else
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Classe</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($absencesNonJustifiees as $absence)
                        <tr>
                            <td class="px-6 py-4">
                                {{ $absence->etudiant->user->nom }} {{ $absence->etudiant->user->prenom }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->seance->anneeClasse->classe->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->seance->matiere->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->seance->date_debut->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('justifications.create', $absence->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded">
                                    Justifier
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $absencesNonJustifiees->links() }}
            </div>
        </div>
    @endif

    {{-- Absences déjà justifiées --}}
    <h2 class="text-2xl font-bold text-gray-700 mt-12">Absences déjà justifiées</h2>
    @if($absencesJustifiees->isEmpty())
        <p class="text-gray-500 italic">Aucune justification enregistrée.</p>
    @else
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Classe</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Justification</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($absencesJustifiees as $absence)
                        <tr>
                            <td class="px-6 py-4">
                                {{ $absence->etudiant->user->nom }} {{ $absence->etudiant->user->prenom }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->seance->anneeClasse->classe->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->seance->matiere->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->seance->date_debut->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $absence->justifications->first()->motif ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $absencesJustifiees->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

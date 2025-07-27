@extends('layouts.professeur')

@section('title', 'Mes Séances')

@section('content')
    <div class="max-w-6xl mx-auto py-8 space-y-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Liste des Séances</h1>
        </div>

        @if ($seances->count() > 0)
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Heure</th>
                            <th class="px-6 py-3">Classe</th>
                            <th class="px-6 py-3">Matière</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($seances as $seance)
                            <tr>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}</td>
                                <td class="px-6 py-4">{{ $seance->classe->nom ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $seance->matiere->nom ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $seance->typeSeance->nom ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('professeur.presences.index', $seance->id) }}"
                                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg shadow">
                                        Marquer Présences
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $seances->links() }}
            </div>
        @else
            <p class="text-gray-600">Aucune séance trouvée.</p>
        @endif
    </div>
@endsection

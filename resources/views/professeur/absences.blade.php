@extends('layouts.professeur')

@section('title', 'Absences Non Justifiées')

@section('content')
    <div class="max-w-7xl mx-auto mt-10 space-y-10">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Absences Non Justifiées</h1>

        <div class="bg-white shadow-lg rounded-xl p-6 border">
            @if ($absences->isEmpty())
                <p class="text-center text-gray-600">Aucune absence non justifiée trouvée pour vos étudiants.</p>
            @else
                <table class="w-full table-auto text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Étudiant</th>
                            <th class="px-4 py-3">Classe</th>
                            <th class="px-4 py-3">Matière</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Heure</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($absences as $absence)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $absence->etudiant->user->nom }}
                                    {{ $absence->etudiant->user->prenom }}</td>
                                <td class="px-4 py-3">{{ $absence->seance->anneeClasse->classe->nom ?? '---' }}</td>
                                <td class="px-4 py-3">{{ $absence->seance->matiere->nom ?? '---' }}</td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($absence->seance->date_debut)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($absence->seance->date_debut)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($absence->seance->date_fin)->format('H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="p-4 border-t">{{ $absences->links() }}</div> --}}
            @endif
        </div>
    </div>
@endsection

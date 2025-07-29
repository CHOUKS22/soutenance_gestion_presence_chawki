@extends('layouts.professeur')

@section('title', 'Mon Emploi du Temps')

@section('content')
<div class="max-w-7xl mx-auto mt-10 space-y-10">

    {{-- <h1 class="text-3xl font-bold mb-6 text-gray-800">Mon Emploi du Temps</h1> --}}

    {{-- Filtre par semaine --}}
    <form method="GET" action="{{ route('professeur.emploi_du_temps') }}" class="mb-6">
        <label for="semaine" class="block mb-2 font-medium text-gray-700">Filtrer par semaine :</label>
        <input type="week" name="semaine" id="semaine" value="{{ request('semaine') }}"
            class="border-gray-300 rounded-md shadow-sm">
        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Appliquer
        </button>
    </form>

    <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-200">
        <table class="w-full table-auto text-sm text-left border-collapse">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3">Jour</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Heure</th>
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3">Matière</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($seances as $seance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($seance->date_debut)->locale('fr_FR')->translatedFormat('l') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                        </td>
                        <td class="px-4 py-3">{{ $seance->anneeClasse->classe->nom ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $seance->matiere->nom ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Aucune séance trouvée pour cette semaine.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

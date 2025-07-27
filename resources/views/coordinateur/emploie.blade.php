@extends('layouts.coordinateur')

@section('title', 'Emploi du Temps')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-8">Emploi du temps de la semaine</h1>

    <!-- Filtres -->
    <form method="GET" class="flex flex-wrap items-end gap-6 mb-8">
        <div>
            <label for="classe_id" class="block text-sm font-semibold text-gray-700">Classe</label>
            <select name="classe_id" id="classe_id" onchange="this.form.submit()"
                class="mt-1 w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ $classe->id == $selectedClasseId ? 'selected' : '' }}>
                        {{ $classe->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="date" class="block text-sm font-semibold text-gray-700">Semaine du</label>
            <input type="date" name="date" id="date" value="{{ $startOfWeek->format('Y-m-d') }}"
                onchange="this.form.submit()"
                class="mt-1 w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </form>

    @if ($seances->count())
        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full table-auto text-sm text-left text-gray-700">
                <thead class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Jour</th>
                        <th class="px-6 py-4">Heure</th>
                        <th class="px-6 py-4">Matière</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Professeur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($seances->sortBy('date_debut') as $seance)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium">
                                {{ \Carbon\Carbon::parse($seance->date_debut)->translatedFormat('l d/m') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-semibold">
                                {{ $seance->matiere->nom ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($seance->typeSeance->nom == 'Présentiel') bg-green-100 text-green-800
                                    @elseif($seance->typeSeance->nom == 'E-learning') bg-yellow-100 text-yellow-800
                                    @elseif($seance->typeSeance->nom == 'Workshop') bg-purple-100 text-purple-800
                                    @else bg-gray-200 text-gray-800 @endif">
                                    {{ $seance->typeSeance->nom ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $seance->professeur->user->prenom ?? '' }} {{ $seance->professeur->user->nom ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="mt-10 text-center text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-3"></i><br>
            Aucune séance programmée pour cette semaine.
        </div>
    @endif
</div>
@endsection

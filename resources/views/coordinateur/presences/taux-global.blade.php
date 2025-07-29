@extends('layouts.coordinateur')

@section('title', 'Taux de présence global par classe')

@section('content')
{{-- Page d'affichages pour le taux de présence global par classe --}}
    <a href="{{ route('coordinateur.presences.statistiques') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
        <i class="fas fa-arrow-left text-xl"></i>
    </a>
    <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6">Taux de présence global par classe</h2>

        <form method="GET" action="{{ route('coordinateur.presences.globalParClasse') }}" class="mb-6">
            <label for="periode" class="font-semibold text-gray-700 mr-2">Période :</label>
            <select name="periode" id="periode" onchange="this.form.submit()"
                class="border border-gray-300 rounded px-3 py-1">
                <option value="semaine" {{ $periode == 'semaine' ? 'selected' : '' }}>Semaine</option>
                <option value="semestre" {{ $periode == 'semestre' ? 'selected' : '' }}>Semestre</option>
                <option value="annee" {{ $periode == 'annee' ? 'selected' : '' }}>Année</option>
            </select>
        </form>

        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4">Classe</th>
                    <th class="px-6 py-4 text-center">Présences</th>
                    <th class="px-6 py-4 text-center">Absences</th>
                    <th class="px-6 py-4 text-center">Taux (%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($donnees as $data)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $data->classe }}</td>
                        <td class="px-6 py-4 text-center">{{ $data->presences }}</td>
                        <td class="px-6 py-4 text-center">{{ $data->absences }}</td>
                        <td
                            class="px-6 py-4 text-center font-bold {{ $data->taux < 30 ? 'text-red-600' : ($data->taux < 80 ? 'text-yellow-500' : 'text-green-600') }}">
                            {{ $data->taux }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucune donnée disponible</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

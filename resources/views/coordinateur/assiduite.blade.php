@extends('layouts.coordinateur')

@section('title', 'Note d’assiduité par matière')

@section('content')
    <div class="max-w-5xl mx-auto mt-8 space-y-6">
        <a href="{{ route('coordinateur.presences.statistiques') }}"
            class="text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        {{-- Formulaire de notes d'assiduite par matiere  --}}
        <form method="GET" action="{{ route('statistiques.assiduite') }}" class="bg-white p-6 rounded-lg shadow-md">
            <label for="etudiant_id" class="block mb-2 font-medium text-gray-700">Choisir un étudiant :</label>
            <select name="etudiant_id" id="etudiant_id" onchange="this.form.submit()"
                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Sélectionner --</option>
                @foreach ($etudiants as $etudiant)
                    <option value="{{ $etudiant->id }}" {{ $etudiant->id == $selectedEtudiantId ? 'selected' : '' }}>
                        {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                    </option>
                @endforeach
            </select>
        </form>

        @if (!empty($notes))
            <table class="w-full table-auto border-collapse border border-gray-300 text-sm mt-6">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Matière</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Total séances</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Présences</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Note / 20</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notes as $note)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $note['matiere'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $note['total'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $note['presences'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $note['note'] }}/20</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif(request()->has('etudiant_id'))
            <p class="text-red-600 mt-4">Aucune donnée d’assiduité trouvée pour cet étudiant.</p>
        @endif

    </div>
@endsection

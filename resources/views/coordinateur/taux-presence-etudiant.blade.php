@extends('layouts.coordinateur')

@section('title', 'Taux de présence par période')
@section('subtitle', 'Consultez le taux de présence d’un étudiant pour une période donnée')

@section('content')
<div class="bg-white p-6 rounded shadow space-y-6">

    <form method="GET" action="{{ route('statistiques.taux-presence-etudiant') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="etudiant_id" class="block font-medium text-sm text-gray-700">Étudiant</label>
                <select name="etudiant_id" id="etudiant_id" required class="mt-1 block w-full border-gray-300 rounded">
                    <option value="">-- Sélectionner un étudiant --</option>
                    @foreach($etudiants as $etudiant)
                        <option value="{{ $etudiant->id }}" {{ $etudiantId == $etudiant->id ? 'selected' : '' }}>
                            {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="periode" class="block font-medium text-sm text-gray-700">Période</label>
                <select name="periode" id="periode" onchange="handlePeriodeChange(this.value)" class="mt-1 block w-full border-gray-300 rounded">
                    <option value="semaine" {{ $periode == 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="semestre" {{ $periode == 'semestre' ? 'selected' : '' }}>Ce semestre</option>
                    <option value="annee" {{ $periode == 'annee' ? 'selected' : '' }}>Cette année</option>
                    <option value="personnalisee" {{ $periode == 'personnalisee' ? 'selected' : '' }}>Période personnalisée</option>
                </select>
            </div>

            <div id="dates-personnalisees" class="{{ $periode == 'personnalisee' ? '' : 'hidden' }}">
                <label class="block font-medium text-sm text-gray-700">Dates personnalisées</label>
                <div class="flex gap-2">
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="mt-1 w-full border-gray-300 rounded">
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="mt-1 w-full border-gray-300 rounded">
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Calculer</button>
        </div>
    </form>

    @if ($etudiantId)
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Résultat</h2>

            @if ($taux !== null)
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left px-4 py-2 border">Étudiant</th>
                            <th class="text-left px-4 py-2 border">Période</th>
                            <th class="text-left px-4 py-2 border">Total séances</th>
                            <th class="text-left px-4 py-2 border">Présences</th>
                            <th class="text-left px-4 py-2 border">Taux</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border">{{ $etudiants->firstWhere('id', $etudiantId)?->user->nom }} {{ $etudiants->firstWhere('id', $etudiantId)?->user->prenom }}</td>
                            <td class="px-4 py-2 border">{{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $totalSeances }}</td>
                            <td class="px-4 py-2 border">{{ $totalPresences }}</td>
                            <td class="px-4 py-2 border font-bold {{ $taux < 70 ? 'text-red-600' : 'text-green-600' }}">{{ $taux }}%</td>
                        </tr>
                    </tbody>
                </table>

                @if ($taux < 70)
                    <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                        ⚠️ L'étudiant est considéré comme <strong>droppé</strong> pour cette période.
                    </div>
                @endif
            @else
                <p class="text-gray-600 mt-2">Aucune présence ni séance enregistrée pour cette période.</p>
            @endif
        </div>
    @endif
</div>

<script>
    function handlePeriodeChange(value) {
        const block = document.getElementById('dates-personnalisees');
        if (value === 'personnalisee') {
            block.classList.remove('hidden');
        } else {
            block.classList.add('hidden');
        }
    }
</script>
@endsection

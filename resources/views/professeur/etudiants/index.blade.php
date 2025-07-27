@extends('layouts.professeur')

@section('title', 'Étudiants assignés')
@section('subtitle', 'Liste des étudiants liés à vos classes')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Étudiants</h2>

    @if ($etudiants->isEmpty())
        <p class="text-gray-600">Aucun étudiant trouvé.</p>
    @else
        <table class="w-full table-auto border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Prénom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Classe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($etudiants as $etudiant)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $etudiant->user->nom }}</td>
                        <td class="px-4 py-2">{{ $etudiant->user->prenom }}</td>
                        <td class="px-4 py-2">{{ $etudiant->user->email }}</td>
                        <td class="px-4 py-2">
                            @foreach ($etudiant->anneeClasses as $anneeClasse)
                                {{ $anneeClasse->classe->nom ?? 'N/A' }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

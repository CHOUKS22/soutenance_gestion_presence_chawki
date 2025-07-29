@extends('layouts.professeur')

@section('title', 'Étudiants assignés')
@section('subtitle', 'Liste des étudiants liés à vos classes')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Étudiants de vos classes</h1>
        <div class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</div>
    </div>

    @if ($etudiants->isEmpty())
        <div class="bg-yellow-50 text-yellow-700 px-4 py-3 rounded-md">
            Aucun étudiant n’est assigné à vos classes pour le moment.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-700 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Prénom</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Classe(s)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-100 text-sm">
                    @foreach ($etudiants as $etudiant)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium">{{ $etudiant->user->nom }}</td>
                            <td class="px-6 py-3">{{ $etudiant->user->prenom }}</td>
                            <td class="px-6 py-3">{{ $etudiant->user->email }}</td>
                            <td class="px-6 py-3">
                                @foreach ($etudiant->anneeClasses as $anneeClasse)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-1 mb-1">
                                        {{ $anneeClasse->classe->nom ?? 'N/A' }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

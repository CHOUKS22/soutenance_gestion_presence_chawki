@extends('layouts.admin')

@section('title', 'Détails de l\'Association Année-Classe')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détails de l'Association</h1>
            <p class="text-gray-600 mt-1"> {{ $anneeClasse->anneeAcademique?->libelle ?? 'Année inconnue' }} -   {{ $anneeClasse->classe?->nom ?? 'Classe inconnue' }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('annees-classes.edit', $anneeClasse) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <a href="{{ route('annees-classes.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- partie info principale --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Informations Générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- annee academique --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Année Académique</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-lg font-semibold text-blue-600"> {{ $anneeClasse->anneeAcademique?->libelle ?? 'Année inconnue' }}</div>
                            <div class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($anneeClasse->anneeAcademique->date_debut)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($anneeClasse->anneeAcademique->date_fin)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- classe --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-lg font-semibold text-green-600">{{ $anneeClasse->classe->nom }}</div>
                        </div>
                    </div>

                    {{-- coordinateur --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Coordinateur</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-lg font-semibold text-purple-600">
                                {{ $anneeClasse->coordinateur->user->nom }} {{ $anneeClasse->coordinateur->user->prenom }}
                            </div>
                            <div class="text-sm text-gray-600">{{ $anneeClasse->coordinateur->user->email }}</div>
                        </div>
                    </div>
                </div>

                {{-- metadata --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Metadonnees</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Cree le :</span>
                            <span class="font-medium">{{ $anneeClasse->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Modifie le :</span>
                            <span class="font-medium">{{ $anneeClasse->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- partie droite --}}
        <div>
            {{-- stats --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Statistiques</h2>
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $anneeClasse->etudiants->count() }}</div>
                        <div class="text-sm text-blue-800">Étudiants inscrits</div>
                    </div>
                </div>
            </div>

            {{-- liste des etudiants --}}
            @if($anneeClasse->etudiants->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Étudiants Inscrits</h2>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($anneeClasse->etudiants as $etudiant)
                    <div class="flex items-center p-2 bg-gray-50 rounded">
                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">
                            {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->nom, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">
                                {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                            </div>
                            <div class="text-sm text-gray-600">{{ $etudiant->user->email }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
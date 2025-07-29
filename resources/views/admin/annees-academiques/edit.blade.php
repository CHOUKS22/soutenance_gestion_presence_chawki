@extends('layouts.admin')

@section('title', 'Modifier l\'Annee Academique')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- titre et boutons de navigation -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier l'Annee Academique</h1>
            <p class="text-gray-600 mt-1">{{ $anneeAcademique->libelle }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('annees-academiques.show', $anneeAcademique) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-eye mr-2"></i>Voir
            </a>
            <a href="{{ route('annees-academiques.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- formulaire principal -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('annees-academiques.update', $anneeAcademique) }}" method="POST" id="anneeForm">
            @csrf
            @method('PUT')

            <!-- champs principaux -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- libelle -->
                <div class="md:col-span-2">
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-2">
                        Libelle de l'annee academique <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="libelle"
                           id="libelle"
                           value="{{ old('libelle', $anneeAcademique->libelle) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required
                           placeholder="Ex: 2024-2025, Annee 2024/2025">
                    @error('libelle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- date de debut -->
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de debut <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="date_debut"
                           id="date_debut"
                           value="{{ old('date_debut', $anneeAcademique->date_debut ? \Carbon\Carbon::parse($anneeAcademique->date_debut)->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('date_debut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- date de fin -->
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="date_fin"
                           id="date_fin"
                           value="{{ old('date_fin', $anneeAcademique->date_fin ? \Carbon\Carbon::parse($anneeAcademique->date_fin)->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('date_fin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- informations complementaires -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h2 class="text-sm font-medium text-gray-800 mb-2">Informations de modification</h2>
                <p><span class="font-medium">Creee le :</span> {{ $anneeAcademique->created_at ? $anneeAcademique->created_at->format('d/m/Y a H:i') : 'Non disponible' }}</p>
                <p><span class="font-medium">Derniere modification :</span> {{ $anneeAcademique->updated_at ? $anneeAcademique->updated_at->format('d/m/Y a H:i') : 'Non disponible' }}</p>
            </div>

            <!-- boutons en bas du formulaire -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('annees-academiques.show', $anneeAcademique) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Mettre a jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

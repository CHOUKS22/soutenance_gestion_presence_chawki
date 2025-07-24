@extends('layouts.coordinateur')

@section('title', 'Modifier la Séance')
@section('subtitle', 'Modification des informations de la séance')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('gestion-seances.show', $seance) }}"
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Modifier la Séance</h1>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="POST" action="{{ route('gestion-seances.update', $seance) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Section: Informations principales -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        Informations Principales
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Classe -->
                        <div>
                            <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <select name="classe_id" id="classe_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('classe_id') border-red-500 @enderror">
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ (old('classe_id', $seance->classe_id) == $classe->id) ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Matière -->
                        <div>
                            <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Matière <span class="text-red-500">*</span>
                            </label>
                            <select name="matiere_id" id="matiere_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('matiere_id') border-red-500 @enderror">
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ (old('matiere_id', $seance->matiere_id) == $matiere->id) ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Professeur -->
                        <div>
                            <label for="professeur_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Professeur <span class="text-red-500">*</span>
                            </label>
                            <select name="professeur_id" id="professeur_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('professeur_id') border-red-500 @enderror">
                                <option value="">Sélectionner un professeur</option>
                                @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ (old('professeur_id', $seance->professeur_id) == $professeur->id) ? 'selected' : '' }}>
                                        {{ $professeur->user->nom }} {{ $professeur->user->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professeur_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type de séance -->
                        <div>
                            <label for="type_seance_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de Séance <span class="text-red-500">*</span>
                            </label>
                            <select name="type_seance_id" id="type_seance_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type_seance_id') border-red-500 @enderror">
                                <option value="">Sélectionner un type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ (old('type_seance_id', $seance->type_seance_id) == $type->id) ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_seance_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut_seance_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select name="statut_seance_id" id="statut_seance_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('statut_seance_id') border-red-500 @enderror">
                                <option value="">Sélectionner un statut</option>
                                @foreach($statuts as $statut)
                                    <option value="{{ $statut->id }}" {{ (old('statut_seance_id', $seance->statut_seance_id) == $statut->id) ? 'selected' : '' }}>
                                        {{ $statut->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('statut_seance_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="semestre_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <select name="semestre_id" id="semestre_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semestre_id') border-red-500 @enderror">
                                <option value="">Sélectionner un semestre</option>
                                @foreach($semestres as $semestre)
                                    <option value="{{ $semestre->id }}" {{ (old('semestre_id', $seance->semestre_id) == $semestre->id) ? 'selected' : '' }}>
                                        {{ $semestre->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semestre_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Planning -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-green-600"></i>
                        Planning
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date et heure de début -->
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et Heure de Début <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="date_debut" id="date_debut" required
                                   value="{{ old('date_debut', \Carbon\Carbon::parse($seance->date_debut)->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_debut') border-red-500 @enderror">
                            @error('date_debut')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date et heure de fin -->
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et Heure de Fin <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="date_fin" id="date_fin" required
                                   value="{{ old('date_fin', \Carbon\Carbon::parse($seance->date_fin)->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_fin') border-red-500 @enderror">
                            @error('date_fin')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Description -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-file-alt mr-3 text-purple-600"></i>
                        Description (Optionnel)
                    </h2>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description de la séance
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  placeholder="Décrivez le contenu ou les objectifs de cette séance..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $seance->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('gestion-seances.show', $seance) }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Mettre à Jour</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validation en temps réel des dates
    document.getElementById('date_debut').addEventListener('change', function() {
        const dateDebut = new Date(this.value);
        const dateFin = document.getElementById('date_fin');

        if (dateFin.value) {
            const dateFinValue = new Date(dateFin.value);
            if (dateFinValue <= dateDebut) {
                // Ajouter 1 heure à la date de début pour la date de fin
                const nouvelleDate = new Date(dateDebut.getTime() + 60 * 60 * 1000);
                dateFin.value = nouvelleDate.toISOString().slice(0, 16);
            }
        }

        // Définir la date minimum pour la date de fin
        dateFin.min = this.value;
    });

    document.getElementById('date_fin').addEventListener('change', function() {
        const dateDebut = document.getElementById('date_debut').value;

        if (dateDebut && new Date(this.value) <= new Date(dateDebut)) {
            alert('La date de fin doit être postérieure à la date de début.');
            this.value = '';
        }
    });
</script>
@endsection

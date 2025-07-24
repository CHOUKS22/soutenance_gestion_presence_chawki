@extends('layouts.coordinateur')

@section('title', 'Nouvelle Séance')
@section('subtitle', 'Planification d\'une nouvelle séance de cours')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">
        <!-- En-tête de la page -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('gestion-seances.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Nouvelle Séance</h1>
            </div>
        </div>

        <!--Créer séance-->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('gestion-seances.store') }}" class="space-y-8">
                @csrf
                <div class="pb-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Informations de la séance
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Classe -->
                        <div>
                            <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <select name="classe_id" id="classe_id" required
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('classe_id') border-red-400 @enderror">
                                <option value="">Choisir une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--Matière-->
                        <div>
                            <label for="matieres_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Matière <span class="text-red-500">*</span>
                            </label>
                            <select name="matieres_id" id="matieres_id" required
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('matieres_id') border-red-400 @enderror">
                                <option value="">Choisir une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matieres_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matieres_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--Professeur-->
                        <div>
                            <label for="professeur_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Professeur <span class="text-red-500">*</span>
                            </label>
                            <select name="professeur_id" id="professeur_id" required
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('professeur_id') border-red-400 @enderror">
                                <option value="">Choisir un professeur</option>
                                @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ old('professeur_id') == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->user->nom }} {{ $professeur->user->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professeur_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--Type de séance-->
                        <div>
                            <label for="type_seance_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type_seance_id" id="type_seance_id" required
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type_seance_id') border-red-400 @enderror">
                                <option value="">Choisir le type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_seance_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_seance_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--Statut-->
                        <div>
                            <label for="statut_seance_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select name="statut_seance_id" id="statut_seance_id" required
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('statut_seance_id') border-red-400 @enderror">
                                <option value="">Choisir le statut</option>
                                @foreach($statuts as $statut)
                                    <option value="{{ $statut->id }}" {{ old('statut_seance_id') == $statut->id ? 'selected' : '' }}>
                                        {{ $statut->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('statut_seance_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--Semestre-->
                        <div>
                            <label for="semestre_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <select name="semestre_id" id="semestre_id" required
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('semestre_id') border-red-400 @enderror">
                                <option value="">Choisir le semestre</option>
                                @foreach($semestres as $semestre)
                                    <option value="{{ $semestre->id }}" {{ old('semestre_id') == $semestre->id ? 'selected' : '' }}>
                                        {{ $semestre->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semestre_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!--Dates et horaires-->
                <div class="pb-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-green-500"></i>
                        Horaires
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Date début -->
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
                                Date et heure de début <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="date_debut" id="date_debut" required
                                   value="{{ old('date_debut') }}"
                                   class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_debut') border-red-400 @enderror">
                            @error('date_debut')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date fin -->
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">
                                Date et heure de fin <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="date_fin" id="date_fin" required
                                   value="{{ old('date_fin') }}"
                                   class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_fin') border-red-400 @enderror">
                            @error('date_fin')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <!--Actions-->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('gestion-seances.index') }}"
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center space-x-1">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Gestion des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');

    dateDebut.addEventListener('change', function() {
        const debut = new Date(this.value);

        if (dateFin.value) {
            const fin = new Date(dateFin.value);
            if (fin <= debut) {
                // Ajouter une heure par défaut
                const nouvelleFin = new Date(debut.getTime() + (60 * 60 * 1000));
                dateFin.value = nouvelleFin.toISOString().slice(0, 16);
            }
        }

        // Mettre à jour la date min pour la fin
        dateFin.min = this.value;
    });

    dateFin.addEventListener('change', function() {
        const debut = dateDebut.value;

        if (debut && new Date(this.value) <= new Date(debut)) {
            alert('La date de fin doit être après la date de début');
            this.value = '';
        }
    });
</script>
@endsection

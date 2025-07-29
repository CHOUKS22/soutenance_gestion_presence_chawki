@extends('layouts.coordinateur')

@section('title', 'Nouvelle Séance')
@section('subtitle', 'Planification d\'une nouvelle séance de cours')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">

        <!-- En-tete -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('seances.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Nouvelle Séance</h1>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('seances.store') }}" class="space-y-8">
                @csrf

                <!-- Informations de la seance -->
                <div class="pb-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Informations de la séance
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Classe -->
                        <div>
                            <label for="annee_classe_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <select name="annee_classe_id" id="annee_classe_id" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('annee_classe_id') border-red-400 @enderror">
                                <option value="">Choisir une classe</option>
                                @foreach ($annees_classes as $ac)
                                    <option value="{{ $ac->id }}" {{ old('annee_classe_id') == $ac->id ? 'selected' : '' }}>
                                        {{ $ac->classe->nom }} ({{ $ac->anneeAcademique->libelle }})
                                    </option>
                                @endforeach
                            </select>
                            @error('annee_classe_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Matiere -->
                        <div>
                            <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Matière <span class="text-red-500">*</span>
                            </label>
                            <select name="matiere_id" id="matiere_id" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('matiere_id') border-red-400 @enderror">
                                <option value="">Choisir une matière</option>
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Professeur -->
                        <div>
                            <label for="professeur_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Professeur <span class="text-red-500">*</span>
                            </label>
                            <select name="professeur_id" id="professeur_id" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('professeur_id') border-red-400 @enderror">
                                <option value="">Choisir un professeur</option>
                                @foreach ($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ old('professeur_id') == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->user->nom }} {{ $professeur->user->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professeur_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type de seance -->
                        <div>
                            <label for="type_seance_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type_seance_id" id="type_seance_id" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('type_seance_id') border-red-400 @enderror">
                                <option value="">Choisir le type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_seance_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_seance_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut_seance_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select name="statut_seance_id" id="statut_seance_id" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('statut_seance_id') border-red-400 @enderror">
                                <option value="">Choisir le statut</option>
                                @foreach ($statuts as $statut)
                                    <option value="{{ $statut->id }}" {{ old('statut_seance_id') == $statut->id ? 'selected' : '' }}>
                                        {{ $statut->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('statut_seance_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="semestre_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <select name="semestre_id" id="semestre_id" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('semestre_id') border-red-400 @enderror">
                                <option value="">Choisir le semestre</option>
                                @foreach ($semestres as $semestre)
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

                <!-- Horaires -->
                <div class="pb-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-green-500"></i>
                        Horaires
                    </h3>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date et heure de début <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="date_debut" id="date_debut" required value="{{ old('date_debut') }}"
                                class="w-full px-3 py-2 border rounded-md @error('date_debut') border-red-400 @enderror">
                            @error('date_debut')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date et heure de fin <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="date_fin" id="date_fin" required value="{{ old('date_fin') }}"
                                class="w-full px-3 py-2 border rounded-md @error('date_fin') border-red-400 @enderror">
                            @error('date_fin')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bloc conditionnel pour report -->
                <div id="reportFields" class="hidden pb-6 border-b">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i>
                        Informations de report
                    </h4>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_reportee" class="block text-sm font-medium text-gray-700 mb-1">Date reportée</label>
                            <input type="date" name="date_reportee" id="date_reportee" value="{{ old('date_reportee') }}"
                                class="w-full px-3 py-2 border rounded-md @error('date_reportee') border-red-400 @enderror">
                            @error('date_reportee')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="heure_debut_report" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                            <input type="time" name="heure_debut_report" id="heure_debut_report" value="{{ old('heure_debut_report') }}"
                                class="w-full px-3 py-2 border rounded-md @error('heure_debut_report') border-red-400 @enderror">
                            @error('heure_debut_report')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="heure_fin_report" class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                            <input type="time" name="heure_fin_report" id="heure_fin_report" value="{{ old('heure_fin_report') }}"
                                class="w-full px-3 py-2 border rounded-md @error('heure_fin_report') border-red-400 @enderror">
                            @error('heure_fin_report')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="commentaire_report" class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                            <textarea name="commentaire_report" id="commentaire_report" rows="3"
                                class="w-full px-3 py-2 border rounded-md @error('commentaire_report') border-red-400 @enderror">{{ old('commentaire_report') }}</textarea>
                            @error('commentaire_report')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('seances.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center space-x-1">
                        <i class="fas fa-save"></i><span>Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const statutSelect = document.getElementById('statut_seance_id');
    const reportFields = document.getElementById('reportFields');

    function getReporteeStatutId() {
        const options = Array.from(statutSelect.options);
        const reportOption = options.find(opt => opt.text.toLowerCase().includes('report'));
        return reportOption ? parseInt(reportOption.value) : null;
    }

    function toggleReportFields() {
        const selectedId = parseInt(statutSelect.value);
        const reporteeId = getReporteeStatutId();
        if (selectedId === reporteeId) {
            reportFields.classList.remove('hidden');
        } else {
            reportFields.classList.add('hidden');
        }
    }

    statutSelect.addEventListener('change', toggleReportFields);
    document.addEventListener('DOMContentLoaded', toggleReportFields);
</script>
@endsection

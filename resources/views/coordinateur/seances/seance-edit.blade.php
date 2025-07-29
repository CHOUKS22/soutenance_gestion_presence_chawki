@extends('layouts.coordinateur')

@section('title', 'Modifier la Séance')
@section('subtitle', 'Modification des informations de la séance')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('seances.show', $seance) }}"
                        class="text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8">
                <form method="POST" action="{{ route('seances.update', $seance) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informations principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- AnneeClasse -->
                        <div>
                            <label for="annee_classe_id" class="block text-sm font-medium text-gray-700">Classe *</label>
                            <select name="annee_classe_id" id="annee_classe_id" required
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner une classe</option>
                                @foreach ($annees_classes as $ac)
                                    <option value="{{ $ac->id }}"
                                        {{ old('annee_classe_id', $seance->annee_classe_id) == $ac->id ? 'selected' : '' }}>
                                        {{ $ac->classe->nom }} ({{ $ac->anneeAcademique->libelle }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Matiere -->
                        <div>
                            <label for="matiere_id" class="block text-sm font-medium text-gray-700">Matière *</label>
                            <select name="matiere_id" id="matiere_id" required
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner une matière</option>
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        {{ old('matiere_id', $seance->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Professeur -->
                        <div>
                            <label for="professeur_id" class="block text-sm font-medium text-gray-700">Professeur *</label>
                            <select name="professeur_id" id="professeur_id" required
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner un professeur</option>
                                @foreach ($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}"
                                        {{ old('professeur_id', $seance->professeur_id) == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->user->nom }} {{ $professeur->user->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type de seance -->
                        <div>
                            <label for="type_seance_id" class="block text-sm font-medium text-gray-700">Type de Séance
                                *</label>
                            <select name="type_seance_id" id="type_seance_id" required
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner un type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('type_seance_id', $seance->type_seance_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut_seance_id" class="block text-sm font-medium text-gray-700">Statut *</label>
                            <select name="statut_seance_id" id="statut_seance_id" required
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner un statut</option>
                                @foreach ($statuts as $statut)
                                    <option value="{{ $statut->id }}"
                                        {{ old('statut_seance_id', $seance->statut_seance_id) == $statut->id ? 'selected' : '' }}>
                                        {{ $statut->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="semestre_id" class="block text-sm font-medium text-gray-700">Semestre *</label>
                            <select name="semestre_id" id="semestre_id" required
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner un semestre</option>
                                @foreach ($semestres as $semestre)
                                    <option value="{{ $semestre->id }}"
                                        {{ old('semestre_id', $seance->semestre_id) == $semestre->id ? 'selected' : '' }}>
                                        {{ $semestre->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date et heures -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date et heure de début <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="date_debut" id="date_debut" required
                                    value="{{ old('date_debut', \Carbon\Carbon::parse($seance->date_debut)->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-3 py-2 border rounded-md @error('date_debut') border-red-400 @enderror">
                                @error('date_debut')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date et heure de fin <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="date_fin" id="date_fin" required
                                    value="{{ old('date_fin', \Carbon\Carbon::parse($seance->date_fin)->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-3 py-2 border rounded-md @error('date_fin') border-red-400 @enderror">
                                @error('date_fin')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Champs de report -->
                    <div id="reportFields"
                        class="border-t pt-6 {{ $seance->statutSeance->libelle === 'Reportée' ? '' : 'hidden' }}">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de report</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_reportee" class="block text-sm font-medium text-gray-700">Date
                                    reportée</label>
                                <input type="date" name="date_reportee" id="date_reportee"
                                    value="{{ old('date_reportee', $seance->date_reportee) }}"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                            </div>
                            <div>
                                <label for="heure_debut_report" class="block text-sm font-medium text-gray-700">Heure début
                                    report</label>
                                <input type="time" name="heure_debut_report" id="heure_debut_report"
                                    value="{{ old('heure_debut_report', $seance->heure_debut_report) }}"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                            </div>
                            <div>
                                <label for="heure_fin_report" class="block text-sm font-medium text-gray-700">Heure fin
                                    report</label>
                                <input type="time" name="heure_fin_report" id="heure_fin_report"
                                    value="{{ old('heure_fin_report', $seance->heure_fin_report) }}"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="commentaire_report"
                                    class="block text-sm font-medium text-gray-700">Commentaire</label>
                                <textarea name="commentaire_report" id="commentaire_report" rows="3"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ old('commentaire_report', $seance->commentaire_report) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg">
                            Enregistrer les modifications
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

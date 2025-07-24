@extends('layouts.admin')

@section('title', 'Modifier le Statut de Séance')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier le Statut de Séance</h1>
            <p class="text-gray-600 mt-1">{{ $statutSeance->nom }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('statuts-seances.show', $statutSeance->id) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-eye mr-2"></i>Voir
            </a>
            <a href="{{ route('statuts-seances.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('statuts-seances.update', $statutSeance->id) }}" method="POST" id="statutForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Libellé -->
                <div class="md:col-span-2">
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-2">
                        Libellé du statut <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="libelle"
                           id="libelle"
                           value="{{ old('libelle', $statutSeance->libelle) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required
                           placeholder="Ex: Planifiée, En cours, Terminée, Annulée">
                    @error('libelle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Description détaillée du statut de séance">{{ old('description', $statutSeance->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Couleur (optionnel) -->
                <div>
                    <label for="couleur" class="block text-sm font-medium text-gray-700 mb-2">
                        Couleur d'affichage
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color"
                               name="couleur"
                               id="couleur"
                               value="{{ old('couleur', $statutSeance->couleur ?? '#3B82F6') }}"
                               class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                        <span class="text-sm text-gray-500">Utilisée pour l'affichage dans les calendriers</span>
                    </div>
                    @error('couleur')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordre d'affichage -->
                <div>
                    <label for="ordre" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordre d'affichage
                    </label>
                    <input type="number"
                           name="ordre"
                           id="ordre"
                           value="{{ old('ordre', $statutSeance->ordre ?? 1) }}"
                           min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="1">
                    <p class="text-xs text-gray-500 mt-1">Plus le nombre est petit, plus le statut apparaît en haut</p>
                    @error('ordre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Aperçu des utilisations -->
            @if($statutSeance->seances && $statutSeance->seances->count() > 0)
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Impact des modifications</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Ce statut est utilisé pour <strong>{{ $statutSeance->seances->count() }} séance(s)</strong>.</p>
                            <p>La modification du nom affectera l'affichage de toutes ces séances.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Suggestions de statuts -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Suggestions de statuts</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <button type="button" onclick="setStatut('Planifiée', '#6B7280')"
                            class="px-3 py-2 bg-gray-100 text-gray-800 rounded-lg text-sm hover:bg-gray-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                            Planifiée
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('En cours', '#10B981')"
                            class="px-3 py-2 bg-green-100 text-green-800 rounded-lg text-sm hover:bg-green-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            En cours
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('Terminée', '#3B82F6')"
                            class="px-3 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm hover:bg-blue-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            Terminée
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('Annulée', '#EF4444')"
                            class="px-3 py-2 bg-red-100 text-red-800 rounded-lg text-sm hover:bg-red-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            Annulée
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('Reportée', '#F59E0B')"
                            class="px-3 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm hover:bg-yellow-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            Reportée
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('Suspendue', '#8B5CF6')"
                            class="px-3 py-2 bg-purple-100 text-purple-800 rounded-lg text-sm hover:bg-purple-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                            Suspendue
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('Brouillon', '#6B7280')"
                            class="px-3 py-2 bg-gray-100 text-gray-800 rounded-lg text-sm hover:bg-gray-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                            Brouillon
                        </div>
                    </button>
                    <button type="button" onclick="setStatut('Validée', '#059669')"
                            class="px-3 py-2 bg-emerald-100 text-emerald-800 rounded-lg text-sm hover:bg-emerald-200 text-left">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-emerald-600 rounded-full mr-2"></div>
                            Validée
                        </div>
                    </button>
                </div>
            </div>

            <!-- Informations de modification -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-800 mb-2">Informations de modification</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Créé le:</span> {{ $statutSeance->created_at ? $statutSeance->created_at->format('d/m/Y à H:i') : 'Non disponible' }}</p>
                    <p><span class="font-medium">Dernière modification:</span> {{ $statutSeance->updated_at ? $statutSeance->updated_at->format('d/m/Y à H:i') : 'Non disponible' }}</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('statuts-seances.show', $statutSeance->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- Zone de suppression -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-red-800">Zone de danger</h3>
                <p class="text-red-600 text-sm mt-1">
                    La suppression de ce statut est irréversible.
                    @if($statutSeance->seances && $statutSeance->seances->count() > 0)
                        Elle affectera {{ $statutSeance->seances->count() }} séance(s) enregistrée(s).
                    @endif
                </p>
            </div>
            <button onclick="confirmDelete()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-trash mr-2"></i>Supprimer
            </button>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
                </div>
            </div>

            <p class="text-gray-500 mb-6">
                Êtes-vous sûr de vouloir supprimer ce statut de séance ?
                @if($statutSeance->seances && $statutSeance->seances->count() > 0)
                    Cette action affectera {{ $statutSeance->seances->count() }} séance(s) enregistrée(s).
                @endif
                Cette action est irréversible.
            </p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <form action="{{ route('statuts-seances.destroy', $statutSeance->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function setStatut(libelle, couleur) {
    document.getElementById('libelle').value = libelle;
    document.getElementById('couleur').value = couleur;
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Aperçu de la couleur
document.getElementById('couleur').addEventListener('change', function() {
    // Optionnel: ajouter un aperçu visuel de la couleur sélectionnée
});
</script>
@endsection

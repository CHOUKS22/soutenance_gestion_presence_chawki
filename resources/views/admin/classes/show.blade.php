@extends('layouts.admin')

@section('title', 'Détails de la Classe')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec le nom et les boutons -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détails de la Classe</h1>
            <p class="text-gray-600 mt-1">{{ $classe->nom }}</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('classes.edit', $classe) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <a href="{{ route('classes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Grille principale : infos + actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Bloc gauche : infos sur la classe -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations Générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Nom de la classe</label>
                        <div class="bg-gray-50 p-3 rounded-lg text-blue-600 font-semibold text-lg">{{ $classe->nom }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Nombre d'étudiants</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-green-600 font-semibold text-lg">0 étudiants</div>
                            <div class="text-sm text-gray-500">Aucun étudiant inscrit pour le moment</div>
                        </div>
                    </div>
                </div>

                <!-- Infos supplémentaires -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Métadonnées</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de création</label>
                            <div class="text-sm text-gray-900">{{ $classe->created_at->format('d/m/Y à H:i') }}</div>
                            <div class="text-sm text-gray-500">{{ $classe->created_at->diffForHumans() }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dernière modification</label>
                            <div class="text-sm text-gray-900">{{ $classe->updated_at->format('d/m/Y à H:i') }}</div>
                            <div class="text-sm text-gray-500">{{ $classe->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloc droit : actions et stats -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('classes.edit', $classe) }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Modifier la classe
                    </a>
                    <button onclick="openDeleteModal()" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>Supprimer la classe
                    </button>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Étudiants inscrits</span>
                        <span class="text-gray-900 font-semibold">0</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Années académiques</span>
                        <span class="text-gray-900 font-semibold">0</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Séances programmées</span>
                        <span class="text-gray-900 font-semibold">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal pour la suppression -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-md shadow-lg w-96 p-6">
        <div class="text-center">
            <div class="w-12 h-12 mx-auto flex items-center justify-center rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmer la suppression</h3>
            <p class="text-sm text-gray-500 mt-2">
                Vous êtes sur le point de supprimer la classe "{{ $classe->nom }}". Cette action ne peut pas être annulée.
            </p>
            <div class="mt-4 flex justify-center gap-2">
                <form action="{{ route('classes.destroy', $classe) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Supprimer
                    </button>
                </form>
                <button onclick="closeDeleteModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour ouvrir la modale
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Fonction pour fermer la modale
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Ferme la modale si on clique en dehors
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection

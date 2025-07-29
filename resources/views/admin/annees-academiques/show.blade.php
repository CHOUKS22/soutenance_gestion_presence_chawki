@extends('layouts.admin')

@section('title', 'Details de l\'Annee Academique')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- en-tete avec le titre et les boutons -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Details de l'Annee Academique</h1>
                <p class="text-gray-600 mt-1">{{ $anneeAcademique->libelle }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('annees-academiques.edit', $anneeAcademique) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('annees-academiques.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- deux colonnes : infos et actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- bloc principal avec les infos -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Informations de l'annee academique</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Libelle</label>
                            <p class="text-lg text-gray-900">{{ $anneeAcademique->libelle }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date de debut</label>
                            <p class="text-lg text-gray-900">
                                {{ $anneeAcademique->date_debut ? \Carbon\Carbon::parse($anneeAcademique->date_debut)->format('d/m/Y') : 'Non definie' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date de fin</label>
                            <p class="text-lg text-gray-900">
                                {{ $anneeAcademique->date_fin ? \Carbon\Carbon::parse($anneeAcademique->date_fin)->format('d/m/Y') : 'Non definie' }}
                            </p>
                        </div>
                    </div>

                    @if ($anneeAcademique->date_debut && $anneeAcademique->date_fin)
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Duree de l'annee academique</h3>
                            <p class="text-blue-700">
                                {{ \Carbon\Carbon::parse($anneeAcademique->date_debut)->diffInDays(\Carbon\Carbon::parse($anneeAcademique->date_fin)) }}
                                jours
                                ({{ \Carbon\Carbon::parse($anneeAcademique->date_debut)->diffInMonths(\Carbon\Carbon::parse($anneeAcademique->date_fin)) }}
                                mois)
                            </p>
                        </div>
                    @endif
                </div>

                <!-- liste des semestres -->
                <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                    <h4 class="text-xl font-semibold mb-4 text-gray-800">Semestres associes</h4>

                    @if ($anneeAcademique->semestres && $anneeAcademique->semestres->count() > 0)
                        <div class="space-y-3">
                            @foreach ($anneeAcademique->semestres as $semestre)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $semestre->libelle }}</p>
                                            <p class="text-sm text-gray-500">
                                                Du
                                                {{ $semestre->date_debut ? \Carbon\Carbon::parse($semestre->date_debut)->format('d/m/Y') : 'N/A' }}
                                                au
                                                {{ $semestre->date_fin ? \Carbon\Carbon::parse($semestre->date_fin)->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>
                                        <a href="{{ route('gestion-semestres.show', $semestre->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-900">Aucun semestre</p>
                            <p class="mt-1 text-sm text-gray-500">Aucun semestre n'est encore associe a cette annee
                                academique.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- colonne de droite pour les actions et stats -->
            <div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <p class="text-xl font-semibold mb-4 text-gray-800">Actions rapides</p>

                    <div class="space-y-3">
                        <a href="{{ route('annees-academiques.edit', $anneeAcademique) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>

                        <button onclick="confirmDelete()"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </div>
                </div>

                <!-- bloc avec les stats et infos systeme -->
                <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                    <p class="text-xl font-semibold mb-4 text-gray-800">Statistiques</p>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Nombre de semestres</span>
                            <span class="text-lg font-semibold text-gray-900">
                                {{ $anneeAcademique->semestres ? $anneeAcademique->semestres->count() : 0 }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Annees-classes associees</span>
                            <span class="text-lg font-semibold text-gray-900">
                                {{ $anneeAcademique->anneesClasses ? $anneeAcademique->anneesClasses->count() : 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-3">Informations systeme</p>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">ID:</span> {{ $anneeAcademique->id }}
                            </div>
                            <div>
                                <span class="font-medium">Creee le:</span>
                                {{ $anneeAcademique->created_at ? $anneeAcademique->created_at->format('d/m/Y a H:i') : 'Non disponible' }}
                            </div>
                            <div>
                                <span class="font-medium">Modifiee le:</span>
                                {{ $anneeAcademique->updated_at ? $anneeAcademique->updated_at->format('d/m/Y a H:i') : 'Non disponible' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- confirmation suppression -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    <p class="ml-3 text-lg font-medium text-gray-900">Confirmer la suppression</p>
                </div>
                <p class="text-gray-500 mb-6">
                    Etes-vous sur de vouloir supprimer cette annee academique ? Cela supprimera aussi tous les semestres et
                    annees-classes lies. Cette action est definitive.
                </p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                        Annuler
                    </button>
                    <form action="{{ route('annees-academiques.destroy', $anneeAcademique) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            Supprimer definitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ouvre le modal
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // ferme le modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // ferme le modal si on clique en dehors
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection

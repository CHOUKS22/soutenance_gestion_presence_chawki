@extends('layouts.admin')

@section('title', 'Details du Semestre')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- entete --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Details du Semestre</h1>
                <p class="text-gray-600 mt-1">{{ $semestre->libelle }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('semestres.edit', $semestre->id) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('semestres.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- bloc principal --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Informations du Semestre</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Libelle</label>
                            <p class="text-lg text-gray-900">{{ $semestre->libelle }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Annee Academique</label>
                            <p class="text-lg text-gray-900">
                                @if ($semestre->anneeAcademique)
                                    {{ $semestre->anneeAcademique->libelle }}
                                @else
                                    <span class="text-gray-500">Non definie</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date de debut</label>
                            <p class="text-lg text-gray-900">
                                {{ $semestre->date_debut ? \Carbon\Carbon::parse($semestre->date_debut)->format('d/m/Y') : 'Non definie' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date de fin</label>
                            <p class="text-lg text-gray-900">
                                {{ $semestre->date_fin ? \Carbon\Carbon::parse($semestre->date_fin)->format('d/m/Y') : 'Non definie' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- actions rapides --}}
            <div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Actions</h2>

                    <div class="space-y-3">
                        <a href="{{ route('semestres.edit', $semestre->id) }}"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>

                        <button onclick="confirmDelete()"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </div>

                    {{-- infos systeme --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Informations systeme</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div><span class="font-medium">ID:</span> {{ $semestre->id }}</div>
                            <div><span class="font-medium">Cree le:</span>
                                {{ $semestre->created_at ? $semestre->created_at->format('d/m/Y a H:i') : 'Non disponible' }}
                            </div>
                            <div><span class="font-medium">Modifie le:</span>
                                {{ $semestre->updated_at ? $semestre->updated_at->format('d/m/Y a H:i') : 'Non disponible' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- modal de confirmation suppression --}}
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
                    Etes-vous sur de vouloir supprimer ce semestre ? Cette action est irreversible.
                </p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                        Annuler
                    </button>
                    <form action="{{ route('semestres.destroy', $semestre->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- script suppression --}}
    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection

@extends('layouts.admin')

@section('title', 'Gestion des Parents')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">

        {{-- en-tete --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Parents</h1>
            <div class="flex space-x-3">
                <button id="addParentBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter Infos Parent</span>
                </button>
            </div>
        </div>

        {{-- messages flash --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- formulaire ajout parent --}}
        <div id="addParentForm" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
            <h2 class="text-xl font-semibold mb-4">Ajouter les informations d'un Parent</h2>
            @if($usersParents->count() > 0)
                <form method="POST" action="{{ route('parents.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Selectionner le parent</label>
                            <select id="user_id" name="user_id" class="w-full px-3 py-2 border rounded-md" required>
                                <option value="">-- Choisir un parent --</option>
                                @foreach($usersParents as $user)
                                    <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->prenom }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Telephone</label>
                            <input type="text" name="telephone" id="telephone" class="w-full px-3 py-2 border rounded-md" required>
                        </div>

                        <div>
                            <label for="type_relation" class="block text-sm font-medium text-gray-700 mb-1">Type de relation</label>
                            <select id="type_relation" name="type_relation" class="w-full px-3 py-2 border rounded-md" required>
                                <option value="">-- Choisir le type de relation --</option>
                                <option value="Pére">Pere</option>
                                <option value="Mére">Mere</option>
                                <option value="garant">Garant</option>
                                <option value="Tuteur">Tuteur</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>

                    {{-- boutons valider / annuler --}}
                    <div class="flex justify-end mt-4 space-x-3">
                        <button type="button" id="cancelAddBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Annuler</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Ajouter</button>
                    </div>
                </form>
            @else
                <div class="text-center py-8 text-gray-500">Aucun utilisateur avec le role "Parent" disponible</div>
            @endif
        </div>

        {{-- tableau des parents --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-4">Liste des Parents</h3>

                @if($parents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom Complet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telephone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Relation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigner Eleve</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($parents as $parent)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $parent->user->nom }} {{ $parent->user->prenom }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $parent->telephone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $parent->type_relation }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('parents.show', $parent) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('parents.edit', $parent) }}" class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></a>
                                                <form method="POST" action="{{ route('parents.destroy', $parent) }}" onsubmit="return confirm('Supprimer ce parent ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        {{-- assignation eleve --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('parents.assign-etudiant') }}" class="flex items-center space-x-2">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                                                <select name="etudiant_id" class="px-2 py-1 border rounded-md text-sm" required>
                                                    <option value="">-- Eleve --</option>
                                                    @foreach($etudiants as $etudiant)
                                                        <option value="{{ $etudiant->id }}">{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 text-sm">
                                                    <i class="fas fa-user-plus mr-1"></i> Assigner
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- pagination --}}
                    <div class="mt-4">{{ $parents->links() }}</div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun parent trouve.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// gestion affichage formulaire ajout
document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('addParentBtn');
    const addForm = document.getElementById('addParentForm');
    const cancelBtn = document.getElementById('cancelAddBtn');

    addBtn.addEventListener('click', () => {
        addForm.classList.remove('hidden');
        addBtn.classList.add('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        addForm.classList.add('hidden');
        addBtn.classList.remove('hidden');
        const form = addForm.querySelector('form');
        if (form) form.reset();
    });
});
</script>
@endsection

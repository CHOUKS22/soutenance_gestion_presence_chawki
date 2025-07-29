@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">

        {{-- entete --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Modifier le Professeur</h1>
            <a href="{{ route('professeurs.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>

        {{-- formulaire de modification --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('professeurs.update', $professeur) }}">
                    @csrf
                    @method('PUT')

                    {{-- infos en lecture seule --}}
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>Informations personnelles
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- nom --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" value="{{ $professeur->user->nom }}" readonly
                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                            </div>

                            {{-- prenom --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prenom</label>
                                <input type="text" value="{{ $professeur->user->prenom }}" readonly
                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                            </div>

                            {{-- email --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="{{ $professeur->user->email }}" readonly
                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                            </div>

                            {{-- filiere --}}
                            <div>
                                <label for="filliere_id" class="block text-sm font-medium text-gray-700 mb-2">Filiere</label>
                                <select id="filliere_id" name="filliere_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selectionner une filiere</option>
                                    @foreach($fillieres as $filliere)
                                        <option value="{{ $filliere->id }}"
                                            {{ old('filliere_id', $professeur->filliere_id) == $filliere->id ? 'selected' : '' }}>
                                            {{ $filliere->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('filliere_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- boutons actions --}}
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('professeurs.index') }}"
                           class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                            <i class="fas fa-times mr-1"></i>Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-save mr-1"></i>Mettre a jour
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

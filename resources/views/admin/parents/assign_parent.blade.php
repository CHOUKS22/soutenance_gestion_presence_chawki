@extends('layouts.admin')

@section('title', 'Assigner un Étudiant')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-xl font-semibold mb-4">Assigner un Étudiant à un Parent</h1>

        {{-- verifie qu'il y a des parents et des etudiants --}}
        @if($parents->count() > 0 && $etudiants->count() > 0)
            <form method="POST" action="{{ route('parents.assign-etudiant') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- choix du parent --}}
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent</label>
                        <select name="parent_id" id="parent_id" class="w-full border rounded-md px-3 py-2" required>
                            <option value="">-- Sélectionner un parent --</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">
                                    {{ $parent->user->nom }} {{ $parent->user->prenom }} ({{ $parent->type_relation }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- choix de l'etudiant --}}
                    <div>
                        <label for="etudiant_id" class="block text-sm font-medium text-gray-700 mb-1">Étudiant</label>
                        <select name="etudiant_id" id="etudiant_id" class="w-full border rounded-md px-3 py-2" required>
                            <option value="">-- Sélectionner un étudiant --</option>
                            @foreach($etudiants as $etudiant)
                                <option value="{{ $etudiant->id }}">
                                    {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- boutons --}}
                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('parents.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Retour</a>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md">Assigner</button>
                </div>
            </form>
        @else
            {{-- cas ou il manque des donnees --}}
            <div class="text-gray-500 text-center py-8">
                @if($parents->count() == 0)
                    Aucun parent disponible. <br>
                @endif
                @if($etudiants->count() == 0)
                    Aucun étudiant disponible.
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

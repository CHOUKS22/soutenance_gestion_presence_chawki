@extends('layouts.coordinateur')

@section('title', 'Justifier une absence')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <h1 class="text-xl font-bold mb-4">Justifier une absence</h1>

    <p class="mb-4">Étudiant : <strong>{{ $absence->etudiant->user->nom }} {{ $absence->etudiant->user->prenom }}</strong></p>

    <form action="{{ route('justifications.store', $absence->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf

        <div>
            <label class="block mb-1 font-medium">Motif</label>
            <input type="text" name="motif" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1 font-medium">Document justificatif (PDF, image)</label>
            <input type="file" name="document" class="w-full">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">Soumettre la justification</button>
    </form>
</div>
@endsection

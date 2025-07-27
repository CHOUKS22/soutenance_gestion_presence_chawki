@extends('layouts.professeur')

@section('title', 'Tableau de bord Professeur')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Bienvenue, {{ $user->nom }} {{ $user->prenom }}</h1>
        <p class="text-gray-600 mb-6">Vous êtes connecté en tant que <span class="font-medium text-blue-600">professeur</span>.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
            <a href="#" class="block bg-blue-100 hover:bg-blue-200 border border-blue-300 rounded-lg p-5 transition duration-200">
                <h2 class="text-lg font-semibold text-blue-700 mb-1">📚 Gérer les séances</h2>
                <p class="text-sm text-gray-600">Accédez à vos séances et planifiez vos cours.</p>
            </a>

            <a href="#" class="block bg-green-100 hover:bg-green-200 border border-green-300 rounded-lg p-5 transition duration-200">
                <h2 class="text-lg font-semibold text-green-700 mb-1">✅ Marquer les présences</h2>
                <p class="text-sm text-gray-600">Saisissez les présences des étudiants pour chaque séance.</p>
            </a>

            <a href="#" class="block bg-yellow-100 hover:bg-yellow-200 border border-yellow-300 rounded-lg p-5 transition duration-200">
                <h2 class="text-lg font-semibold text-yellow-700 mb-1">📄 Voir les justifications</h2>
                <p class="text-sm text-gray-600">Consultez les justifications des absences des étudiants.</p>
            </a>
        </div>
    </div>
</div>
@endsection

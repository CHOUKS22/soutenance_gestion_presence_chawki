@extends('layouts.coordinateur')

@section('title', 'Statistiques')

@section('content')
{{-- Page d'affichages pour les statistiques --}}
    <div class="max-w-4xl mx-auto mt-10 space-y-6">
        {{-- Bouton 1 --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Assiduité par matière</h2>
            <a href="{{ route('statistiques.assiduite') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Voir les statistiques d’assiduité
            </a>
        </div>

        {{-- Bouton 2 --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Taux de présence par étudiant</h2>
            <a href="{{ route('statistiques.taux-presence-etudiant') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Voir les taux de présence par étudiant
            </a>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Taux global par classe</h2>
            <a href="{{ route('coordinateur.presences.globalParClasse') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Voir les taux global par classe
            </a>
        </div>
        {{-- Bouton 3 --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Graphique par classe (filtrable par période)</h2>
            <a href="{{ route('presence.selection') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Sélectionner une classe
            </a>
        </div>

        {{-- Bouton 4 --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Taux de présence global par classe</h2>
            <a href="{{ route('coordinateur.presences.parClasse') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Voir le taux global par classe
            </a>
        </div>

        {{-- Bouton 5 --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Volume d’heures par semestre</h2>
            <a href="{{ route('coordinateur.presences.volumeCours') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Voir le volume par semestre
            </a>
        </div>

        {{-- Bouton 6 --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Graphique cumulé des volumes d’heures</h2>
            <a href="{{ route('coordinateur.presences.volumeCoursCumule') }}"
                class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Voir le graphique cumulé
            </a>
        </div>


    </div>
@endsection

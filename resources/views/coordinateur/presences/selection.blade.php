@extends('layouts.coordinateur')

@section('title', 'Sélection de classe')

@section('content')
{{--Selectionnez la classe--}}
    <a href="{{ route('coordinateur.presences.statistiques') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
        <i class="fas fa-arrow-left text-xl"></i>
    </a>
    <div class="max-w-3xl mx-auto mt-8">
        <h2 class="text-lg font-semibold mb-4">Sélectionner une classe pour voir les statistiques</h2>
        <form method="GET" action="">
            <select onchange="if(this.value) window.location.href=this.value" class="w-full p-3 border rounded">
                <option disabled selected>-- Choisir une classe --</option>
                @foreach ($classes as $classe)
                    <option value="{{ route('presence.graphique', $classe->id) }}">
                        {{ $classe->nom }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
@endsection

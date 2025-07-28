@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="p-6">

            {{-- titre et bouton d'ajout --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Gestion des Années-Classes</h1>
                <a href="{{ route('annees-classes.create') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Association</span>
                </a>
            </div>

            {{-- stats generales --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Associations</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $anneesClasses->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-link text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    {{-- <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Classes Actives</p>
                        <p class="text-3xl font-bold text-green-600">{{ $anneesClasses->pluck('classe_id')->unique()->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-school text-green-600 text-xl"></i>
                    </div>
                </div> --}}
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Coordinateurs</p>
                            <p class="text-3xl font-bold text-purple-600">
                                {{ $anneesClasses->pluck('coordinateur_id')->unique()->count() }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Étudiants</p>
                            <p class="text-3xl font-bold text-orange-600">
                                {{ $anneesClasses->sum(fn($ac) => $ac->etudiants ? $ac->etudiants->count() : 0) }}
                            </p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="fas fa-users text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- filtres simples --}}
            {{-- <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                {{-- filtre annee --}}
            {{-- <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Année Académique:</label>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les années</option>
                        @foreach ($anneesClasses->pluck('anneeAcademique')->unique()->sortByDesc('libelle') as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                        @endforeach
                    </select>
                </div> --}}

            {{-- filtre classe --}}
            {{-- <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Classe:</label>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les classes</option>
                        @foreach ($anneesClasses->pluck('classe')->unique()->sortBy('nom') as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>  --}}

            {{-- tableau des associations --}}
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Liste des Associations Années-Classes</h2>
                </div>

                @if ($anneesClasses->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Année
                                        Académique</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Coordinateur
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Étudiants
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($anneesClasses as $anneeClasse)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">
                                                        {{ $anneeClasse->anneeAcademique->libelle ?? 'N/A' }}</h3>
                                                    <p class="text-sm text-gray-500">
                                                        {{ optional($anneeClasse->anneeAcademique->date_debut)->format('d/m/Y') }}
                                                        -
                                                        {{ optional($anneeClasse->anneeAcademique->date_fin)->format('d/m/Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                                    <i class="fas fa-school text-green-600"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">
                                                        {{ $anneeClasse->classe->nom ?? 'N/A' }}</h3>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $anneeClasse->classe->niveau ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                                    <i class="fas fa-user-tie text-purple-600"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">
                                                        {{ $anneeClasse->coordinateur->user->nom ?? 'N/A' }}
                                                        {{ $anneeClasse->coordinateur->user->prenom ?? '' }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $anneeClasse->coordinateur->user->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 rounded-full">
                                                {{ $anneeClasse->etudiants ? $anneeClasse->etudiants->count() : 0 }}
                                                étudiants
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $anneeClasse->created_at->format('d/m/Y') }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('annees-classes.show', $anneeClasse->id) }}"
                                                    class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors"
                                                    title="Voir les détails">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <a href="{{ route('annees-classes.edit', $anneeClasse->id) }}"
                                                    class="bg-yellow-600 text-white p-2 rounded-lg hover:bg-yellow-700 transition-colors"
                                                    title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('annees-classes.destroy', $anneeClasse->id) }}"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Supprimer cette association ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition-colors"
                                                        title="Supprimer">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                         @if ($anneesClasses->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $anneesClasses->links() }}
                        </div>
                    @endif
                    </div>
                @else
                    {{-- bloc vide --}}
                    <div class="p-12 text-center">
                        <div class="p-4 bg-gray-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-link text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune association annee-classe</h3>
                        <p class="text-gray-500 mb-6">Ajoutez votre premiere association ici.</p>
                        <a href="{{ route('annees-classes.create') }}"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Créer une Association</span>
                        </a>
                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection

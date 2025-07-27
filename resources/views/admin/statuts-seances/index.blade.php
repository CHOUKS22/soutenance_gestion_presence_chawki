@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Gestion des Statuts de Séances</h1>
                <a href="{{ route('statuts-seances.create') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>Nouveau Statut</span>
                </a>
            </div>


            <!-- Liste des statuts -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Liste des Statuts de Séances</h2>
                </div>

                @if ($statutsSeances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Description
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Séances</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($statutsSeances as $statut)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                                    <i class="fas fa-flag text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">{{ $statut->libelle }}</h3>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-gray-700 text-sm">
                                                {{ $statut->description ? Str::limit($statut->description, 80) : 'Aucune description' }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-sm font-medium bg-purple-100 text-purple-800 rounded-full">
                                                {{ $statut->seances ? $statut->seances->count() : 0 }} séances
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $statut->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                {{-- <a href="{{ route('statuts-seances.show', $statut->id) }}"
                                               class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors"
                                               title="Voir les détails">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>7 --}}
                                                <a href="{{ route('statuts-seances.edit', $statut->id) }}"
                                                    class="bg-yellow-600 text-white p-2 rounded-lg hover:bg-yellow-700 transition-colors"
                                                    title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('statuts-seances.destroy', $statut->id) }}"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce statut ?')">
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
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="p-4 bg-gray-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-flag text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun statut de séance</h3>
                        <p class="text-gray-500 mb-6">Commencez par créer votre premier statut de séance.</p>
                        <a href="{{ route('statuts-seances.create') }}"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Créer un Statut</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

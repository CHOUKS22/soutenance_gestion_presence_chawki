@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Classes</h1>
            <a href="{{ route('classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Nouvelle Classe</span>
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-4 rounded-lg shadow flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-school text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-xl font-bold">{{ $classes->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-graduate text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Étudiants inscrits</p>
                    <p class="text-xl font-bold">0</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Récemment créées</p>
                    <p class="text-xl font-bold">{{ $classes->where('created_at', '>=', now()->subDays(7))->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tableau des classes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Nom</th>
                            {{-- <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Étudiants</th> --}}
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Créée le</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($classes as $classe)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $classe->nom }}</td>
                                {{-- <td class="px-6 py-4">
                                    <span class="text-xs text-green-800 bg-green-100 px-2 py-1 rounded-full">0 étudiants</span>
                                </td> --}}
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $classe->created_at->format('d/m/Y à H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('classes.show', $classe) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('classes.edit', $classe) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('classes.destroy', $classe) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette classe ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center px-6 py-12 text-gray-500">
                                    <i class="fas fa-school text-3xl mb-2"></i>
                                    <p>Aucune classe enregistrée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($classes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $classes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

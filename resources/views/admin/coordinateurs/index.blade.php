@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-7xl mx-auto">

        <!-- Titre et bouton ajouter -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Coordinateurs</h1>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                <a href="{{ route('coordinateurs.create') }}">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter Infos Coordinateur</span>
                </a>
            </button>
        </div>

        <!-- Messages de session -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                    <svg class="fill-current h-6 w-6 text-green-500" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 01-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 111.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 111.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Liste des coordinateurs -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Liste des Coordinateurs</h2>

                @if($coordinateurs->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom Complet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($coordinateurs as $coordinateur)
                                    <tr>
                                        <td class="px-6 py-4">
                                            @if($coordinateur->user->photo)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $coordinateur->user->photo) }}" alt="{{ $coordinateur->user->nom }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $coordinateur->user->nom }} {{ $coordinateur->user->prenom }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $coordinateur->user->email }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                {{ ucfirst($coordinateur->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('coordinateurs.show', $coordinateur) }}" class="text-blue-600 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('coordinateurs.edit', $coordinateur) }}" class="text-yellow-600 bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-md">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('coordinateurs.destroy', $coordinateur) }}" onsubmit="return confirm('Êtes-vous sûr ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $coordinateurs->links() }}
                    </div>
                @else
                    <!-- Message si aucun coordinateur -->
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucun coordinateur trouvé</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>

    // Fermer les alertes si on clique sur la croix
    document.querySelectorAll('[role="alert"] svg')?.forEach(btn => {
        btn.addEventListener('click', e => {
            e.target.closest('[role="alert"]').remove();
        });
    });

</script>
@endsection

@extends('layouts.admin')

@section('title', 'Gestion des Annees Academiques')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-7xl mx-auto">
        <!-- titre de la page avec bouton de creation -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Annees Academiques</h1>
            <a href="{{ route('annees-academiques.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Creer une Annee</span>
            </a>
        </div>

        <!-- message flash de succes -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <span>{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.172 7.066 4.238a1 1 0 10-1.414 1.414L8.586 8.586l-2.934 2.934a1 1 0 001.414 1.414L10 10.828l2.934 2.934a1 1 0 001.414-1.414L11.414 8.586l2.934-2.934z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- tableau des annees -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Liste des Annees Academiques</h2>
                @if($anneesAcademiques->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libelle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duree</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($anneesAcademiques as $annee)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $annee->libelle }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($annee->date_debut)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($annee->date_fin)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($annee->date_debut)->diffInDays(\Carbon\Carbon::parse($annee->date_fin)) }} jours
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('annees-academiques.show', $annee) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('annees-academiques.edit', $annee) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('annees-academiques.destroy', $annee) }}" onsubmit="return confirm('Supprimer cette annee ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
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
                    <!-- pagination -->
                    <div class="mt-4">
                        {{ $anneesAcademiques->links() }}
                    </div>
                @else
                    <!-- message si aucune donnee -->
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucune annee academique trouvee</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ici on ferme l'alerte de succes quand on clique sur la croix
    document.querySelectorAll('[role="alert"] svg').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('[role="alert"]').remove());
    });
</script>
@endsection

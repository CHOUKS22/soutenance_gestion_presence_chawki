@extends('layouts.admin')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ajouter un utilisateur</h1>
            <p class="text-gray-600">Remplissez les champs ci-dessous pour créer un nouvel utilisateur</p>
        </div>
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Informations de l'utilisateur</h2>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('prenom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rôle -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700">Rôle *</label>
                    <select id="role_id" name="role_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->nom) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg file:bg-blue-50 file:text-blue-700 file:rounded-lg file:px-4 file:py-2 file:border-0">
                    <p class="text-xs text-gray-500 mt-1">Formats : JPG, PNG, GIF - Max 2MB</p>
                    @error('photo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div id="photoPreview" class="mt-3"></div>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="window.location='{{ route('users.index') }}'"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Aperçu image sélectionnée
document.getElementById('photo')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
        document.getElementById('photoPreview').innerHTML = `
            <div class="relative inline-block">
                <img src="${event.target.result}" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300">
                <button type="button" onclick="removePhotoPreview()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
    };
    reader.readAsDataURL(file);
});

function removePhotoPreview() {
    document.getElementById('photo').value = '';
    document.getElementById('photoPreview').innerHTML = '';
}
</script>

@if($errors->any())
<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('nom')?.focus();
    });
</script>
@endif
@endsection

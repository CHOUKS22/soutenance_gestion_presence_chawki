@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Modifier l'utilisateur</h1>
            <p class="text-gray-600">Mettre à jour les informations de {{ $user->nom }} {{ $user->prenom }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('users.show', $user) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-eye mr-1"></i>Voir
            </a>
            <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-list mr-1"></i>Liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-lg shadow-sm border">
        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Photo actuelle -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Photo actuelle</p>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-blue-100 flex justify-center items-center">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo" class="w-full h-full object-cover">
                        @else
                            <span class="text-blue-600 font-semibold">{{ substr($user->nom, 0, 1) }}{{ substr($user->prenom, 0, 1) }}</span>
                        @endif
                    </div>
                    <span class="text-sm text-gray-600">
                        @if($user->photo)
                            <i class="fas fa-check-circle text-green-600 mr-1"></i>Photo existante
                        @else
                            <i class="fas fa-times-circle text-red-600 mr-1"></i>Aucune photo
                        @endif
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('nom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('prenom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Rôle -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                    <select id="role_id" name="role_id" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Choisir un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->nom) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nouvelle photo -->
                <div class="md:col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Nouvelle photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 file:bg-blue-50 file:text-blue-700">
                    <p class="text-xs text-gray-500 mt-1">Laisser vide pour ne pas changer la photo</p>
                    <div id="photoPreview" class="mt-3"></div>
                    @error('photo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver le mot de passe actuel</p>
                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Infos supplémentaires -->
            <div class="mt-6 bg-blue-50 p-4 rounded-lg text-sm text-gray-700">
                <p><strong>ID :</strong> #{{ $user->id }}</p>
                <p><strong>Créé le :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Dernière modification :</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}</p>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center mt-6 pt-6 border-t">
                <a href="{{ route('users.show', $user) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                    <i class="fas fa-times mr-1"></i>Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Aperçu image
document.getElementById('photo')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
        let preview = document.getElementById('photoPreview');
        preview.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="${event.target.result}" class="w-16 h-16 object-cover rounded-full border-2 border-green-300">
                    <button type="button" onclick="removePhotoPreview()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="text-green-800 text-sm">
                    <i class="fas fa-check-circle mr-1"></i>Nouvelle photo sélectionnée
                </div>
            </div>`;
    };
    reader.readAsDataURL(file);
});

function removePhotoPreview() {
    document.getElementById('photo').value = '';
    document.getElementById('photoPreview').innerHTML = '';
}

// Mot de passe requis si champ rempli
document.getElementById('password')?.addEventListener('input', function () {
    const confirm = document.getElementById('password_confirmation');
    if (this.value.length > 0) {
        confirm.setAttribute('required', true);
    } else {
        confirm.removeAttribute('required');
    }
});
</script>
@endsection

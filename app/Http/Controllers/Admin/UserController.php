<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\User;
use App\Models\Role;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Models\Statut_seance;
use App\Models\Type_seance;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Affiche la liste des utilisateurs avec leurs roles
    public function index()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    // Formulaire pour ajouter un nouvel utilisateur
    public function create()
    {
        $roles = Role::all();
        $users = User::with('role')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.create', compact('roles', 'users'));
    }

    // Enregistre un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;

        // Si une photo est envoyee on la stocke
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users/photos', 'public');
        }

        // Creation de l'utilisateur
        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'photo' => $photoPath,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur cree avec succes.');
    }

    // Affiche les details d'un utilisateur
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Formulaire pour modifier un utilisateur
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Met a jour un utilisateur
    public function update(Request $request, User $user)
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Valider le mot de passe seulement s'il est rempli
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $updateData = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        // Modifier le mot de passe si un nouveau est fourni
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        // Changer la photo si une nouvelle est envoyee
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $updateData['photo'] = $request->file('photo')->store('users/photos', 'public');
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'Utilisateur modifie avec succes.');
    }

    // Supprime un utilisateur
    public function destroy(User $user)
    {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprime avec succes.');
    }
}

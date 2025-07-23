<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Affiche tous les roles avec le nombre d'utilisateurs associes
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('created_at', 'desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    // Affiche les details d'un role specifique
    public function show(Role $role)
    {
        $role->load('users');
        return view('admin.roles.show', compact('role'));
    }

    // Affiche le formulaire pour ajouter un nouveau role
    public function create()
    {
        return view('admin.roles.create');
    }

    // Enregistre un nouveau role
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:1000',
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role ajoute avec succes.');
    }

    // Affiche le formulaire pour modifier un role
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    // Met a jour les infos d'un role existant
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:roles,nom,' . $role->id,
            'description' => 'required|string|max:1000',
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role modifie avec succes.');
    }

    // Supprime un role uniquement s'il n'est pas assigne a des utilisateurs
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Ce role est utilise par des utilisateurs.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role supprime avec succes.');
    }
}

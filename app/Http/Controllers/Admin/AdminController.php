<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // On recupere les admins avec leur user, tri du plus recent au plus vieux
        $listAdmins = Admin::with('user')->latest()->paginate(10);

        // On cherche les users qui sont admin mais pas encore dans la table Admin
        $roleAdmin = Role::where('nom', 'Administrateur')->first();
        $usersSansAdmin = [];
        if ($roleAdmin) {
            $usersSansAdmin = User::where('role_id', $roleAdmin->id)
                ->whereNotIn('id', Admin::pluck('user_id'))
                ->get();
        }

        // On passe les deux listes a la vue
        return view('admin.admins.admin', [
            'admins' => $listAdmins,
            'usersAdmins' => $usersSansAdmin,
        ]);
    }

    public function create()
    {
        // Meme logique que index, on prep la liste pour le formulaire
        $listAdmins = Admin::with('user')->latest()->paginate(10);

        $roleAdmin = Role::where('nom', 'Administrateur')->first();
        $usersSansAdmin = [];
        if ($roleAdmin) {
            $usersSansAdmin = User::where('role_id', $roleAdmin->id)
                ->whereNotIn('id', Admin::pluck('user_id'))
                ->get();
        }

        return view('admin.admins.admin', [
            'admins' => $listAdmins,
            'usersAdmins' => $usersSansAdmin,
        ]);
    }

    public function store(Request $adm)
    {
        // On verifie que les champs sont bons
        $adm->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:super admin,admin',
        ]);

        // On regarde si c'est pas deja admin
        if (Admin::where('user_id', $adm->user_id)->exists()) {
            return back()->with('error', 'User deja admin.');
        }

        // On cree la ligne admin
        Admin::create([
            'user_id' => $adm->user_id,
            'role' => $adm->role,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin ajoute.');
    }

    public function show($id)
    {
        // On recupere un admin et ses infos
        $admin = Admin::with('user.role')->findOrFail($id);
        return view('admin.admins.admin-show', compact('admin'));
    }

    public function edit($id)
    {
        // On recupere pour le formulaire d'edit
        $admin = Admin::with('user.role')->findOrFail($id);
        return view('admin.admins.admin-edit', compact('admin'));
    }

    public function update(Request $ad, $id)
    {
        // On check le champ role avant de modifier
        $ad->validate([
            'role' => 'required|in:super admin,admin',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->role = $ad->role;
        $admin->save();

        return redirect()->route('admins.show', $admin->id)->with('success', 'Admin modifie.');
    }

    public function destroy($id)
    {
        try {
            // On supprime juste la ligne admin, pas le user
            $admin = Admin::findOrFail($id);
            $admin->delete();
            return redirect()->route('admins.index')->with('success', 'Admin supprime.');
        } catch (\Exception $err) {
            return redirect()->route('admins.index')->with('error', 'Erreur suppression: ' . $err->getMessage());
        }
    }
}

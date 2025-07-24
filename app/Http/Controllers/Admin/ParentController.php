<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parent_model;
use App\Models\Role;
use App\Models\User;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    // Liste tous les parents et les infos utiles
    public function index()
    {
        $parents = Parent_model::with(['user', 'etudiants.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Recuperer les users avec le role Parent mais sans infos parent
        $roleParent = Role::where('nom', 'Parent')->first();
        $usersParents = [];

        if ($roleParent) {
            $usersParents = User::where('role_id', $roleParent->id)
                ->whereNotIn('id', Parent_model::pluck('user_id'))
                ->get();
        }

        // Tous les etudiants disponibles
        $etudiants = Etudiant::with(['user', 'parents.user'])->get();

        return view('admin.parents.parent', compact('parents', 'usersParents', 'etudiants'));
    }

    // Formulaire pour creer un parent
    public function create()
    {
        $parents = Parent_model::with(['user', 'etudiants.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $roleParent = Role::where('nom', 'Parent')->first();
        $usersParents = [];

        if ($roleParent) {
            $usersParents = User::where('role_id', $roleParent->id)
                ->whereNotIn('id', Parent_model::pluck('user_id'))
                ->get();
        }

        $etudiants = Etudiant::with('user')->get();

        return view('admin.parents.parent', compact('parents', 'usersParents', 'etudiants'));
    }

    // Enregistrer un nouveau parent
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'telephone' => 'required|string|max:20',
            'type_relation' => 'required|in:Pére,Mére,garant,Tuteur,Autre',
        ]);

        // Verifier si ce user est deja parent
        $existingParent = Parent_model::where('user_id', $request->user_id)->first();
        if ($existingParent) {
            return redirect()->back()->with('error', 'Cet utilisateur a deja des informations de parent.');
        }

        Parent_model::create([
            'user_id' => $request->user_id,
            'telephone' => $request->telephone,
            'type_relation' => $request->type_relation,
        ]);

        return redirect()->route('parents.index')->with('success', 'Informations du parent creees avec succes.');
    }

    // Affiche les infos d'un parent
    public function show(Parent_model $parent)
    {
        $parent->load(['user.role', 'etudiants.user']);
        return view('admin.parents.show', compact('parent'));
    }

    // Formulaire de modification
    public function edit(Parent_model $parent)
    {
        $parent->load('user.role');
        return view('admin.parents.edit', compact('parent'));
    }

    // Mettre a jour les infos d'un parent
    public function update(Request $request, Parent_model $parent)
    {
        $request->validate([
            'telephone' => 'required|string|max:20',
            'type_relation' => 'required|in:Pére,Mére,garant,Tuteur,Autre',
        ]);

        $parent->update([
            'telephone' => $request->telephone,
            'type_relation' => $request->type_relation,
        ]);

        return redirect()->route('parents.show', $parent)->with('success', 'Informations du parent modifiees avec succes.');
    }

    // Supprimer un parent (sans toucher au user)
    public function destroy(Parent_model $parent)
    {
        $parent->delete();

        return redirect()->route('parents.index')->with('success', 'Informations du parent supprimees avec succes.');
    }

    // Associer un etudiant a un parent
    public function assignEtudiant(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parents,id',
            'etudiant_id' => 'required|exists:etudiants,id',
        ]);

        $parent = Parent_model::find($request->parent_id);
        $etudiant = Etudiant::find($request->etudiant_id);

        // Verifie si l'etudiant est deja lie a ce parent
        if ($parent->etudiants()->where('etudiant_id', $etudiant->id)->exists()) {
            return redirect()->back()->with('error', 'Cet etudiant est deja assigne a ce parent.');
        }

        $parent->etudiants()->attach($etudiant->id);

        return redirect()->back()->with('success', 'Etudiant assigne avec succes au parent.');
    }

    // Retirer un etudiant d'un parent
    public function unassignEtudiant(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parents,id',
            'etudiant_id' => 'required|exists:etudiants,id',
        ]);

        $parent = Parent_model::find($request->parent_id);
        $etudiant = Etudiant::find($request->etudiant_id);

        $parent->etudiants()->detach($etudiant->id);

        return redirect()->back()->with('success', 'Etudiant desassigne avec succes du parent.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professeur;
use App\Models\Role;
use App\Models\User;
use App\Models\Filliere;
use Illuminate\Http\Request;

class ProfesseurController extends Controller
{
    // Affiche la liste des professeurs avec user et filiere
    public function index()
    {
        $professeurs = Professeur::with(['user', 'filliere'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Recuperer les users avec role Professeur mais sans info professeur
        $roleProfesseur = Role::where('nom', 'Professeur')->first();
        $usersProfesseurs = [];

        if ($roleProfesseur) {
            $usersProfesseurs = User::where('role_id', $roleProfesseur->id)
                ->whereNotIn('id', Professeur::pluck('user_id'))
                ->get();
        }

        // Recuperer toutes les filieres
        $fillieres = Filliere::all();

        return view('admin.professeurs.professeur', compact('professeurs', 'usersProfesseurs', 'fillieres'));
    }

    // Formulaire pour ajouter un professeur
    public function create()
    {
        $professeurs = Professeur::with(['user', 'filliere'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $roleProfesseur = Role::where('nom', 'Professeur')->first();
        $usersProfesseurs = [];

        if ($roleProfesseur) {
            $usersProfesseurs = User::where('role_id', $roleProfesseur->id)
                ->whereNotIn('id', Professeur::pluck('user_id'))
                ->get();
        }

        $fillieres = Filliere::all();

        return view('admin.professeurs.professeur', compact('professeurs', 'usersProfesseurs', 'fillieres'));
    }

    // Enregistrer un nouveau professeur
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'filliere_id' => 'required|exists:fillieres,id',
        ]);

        // Verifie si ce user est deja professeur
        $professeurexiste = Professeur::where('user_id', $request->user_id)->first();
        if ($professeurexiste) {
            return redirect()->back()->with('error', 'Cet utilisateur a deja des informations de professeur.');
        }

        Professeur::create([
            'user_id' => $request->user_id,
            'filliere_id' => $request->filliere_id,
        ]);

        return redirect()->route('professeurs.index')->with('success', 'Informations du professeur creees avec succes.');
    }

    // Affiche un professeur avec ses infos
    public function show(Professeur $professeur)
    {
        $professeur = $professeur->load(['user.role', 'filliere']);
        return view('admin.professeurs.show', compact('professeur'));
    }

    // Formulaire de modification d'un professeur
    public function edit(Professeur $professeur)
    {
        $professeur = $professeur->load(['user.role', 'filliere']);
        $fillieres = Filliere::all();

        return view('admin.professeurs.edit', compact('professeur', 'fillieres'));
    }

    // Mettre a jour un professeur
    public function update(Request $request, Professeur $professeur)
    {
        $request->validate([
            'filliere_id' => 'required|exists:fillieres,id',
        ]);

        $professeur->update([
            'filliere_id' => $request->filliere_id,
        ]);

        return redirect()->route('professeurs.show', $professeur)->with('success', 'Informations du professeur modifiees avec succes.');
    }

    // Supprimer les infos du professeur (pas le user)
    public function destroy(Professeur $professeur)
    {
        $professeur->delete();

        return redirect()->route('professeurs.index')->with('success', 'Informations du professeur supprimees avec succes.');
    }
}

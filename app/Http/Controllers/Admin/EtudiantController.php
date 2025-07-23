<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    // Affiche la liste des etudiants avec pagination
    public function index()
    {
        $etudiants = Etudiant::with('user')->orderBy('created_at', 'desc')->paginate(10);

        // On recupere les utilisateurs qui sont etudiants mais n'ont pas encore d'infos dans la table etudiants
        $roleEtudiant = Role::where('nom', 'Etudiant')->first();
        $usersEtudiants = [];

        if ($roleEtudiant) {
            $usersEtudiants = User::where('role_id', $roleEtudiant->id)
                ->whereNotIn('id', Etudiant::pluck('user_id'))
                ->get();
        }

        return view('admin.etudiants.index', compact('etudiants', 'usersEtudiants'));
    }

    // Affiche le formulaire pour ajouter un etudiant
    public function create()
    {
        $etudiants = Etudiant::with('user')->orderBy('created_at', 'desc')->paginate(10);

        // On recupere les utilisateurs avec le role Etudiant qui ne sont pas encore enregistre
        $roleEtudiant = Role::where('nom', 'Etudiant')->first();
        $usersEtudiants = [];

        if ($roleEtudiant) {
            $usersEtudiants = User::where('role_id', $roleEtudiant->id)
                ->whereNotIn('id', Etudiant::pluck('user_id'))
                ->get();
        }

        return view('admin.etudiants.create', compact('etudiants', 'usersEtudiants'));
    }

    // Enregistre les infos d'un etudiant
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
        ]);

        // On verifie que le role Etudiant existe bien
        $roleEtudiant = Role::where('nom', 'Etudiant')->first();
        if (!$roleEtudiant) {
            return redirect()->back()->with('error', 'Le role etudiant est introuvable.');
        }

        // On verifie que l'utilisateur a bien ce role
        $user = User::find($request->user_id);
        if (!$user || $user->role_id !== $roleEtudiant->id) {
            return redirect()->back()->with('error', 'L\'utilisateur selectionne n\'est pas un etudiant.');
        }

        // Si l'etudiant existe deja on bloque
        $etudiantExistant = Etudiant::where('user_id', $request->user_id)->first();
        if ($etudiantExistant) {
            return redirect()->back()->with('error', 'Cet etudiant est deja enregistre.');
        }

        // On cree l'etudiant
        Etudiant::create([
            'user_id' => $request->user_id,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('etudiants.index')->with('success', 'Etudiant ajoute avec succes.');
    }

    // Affiche les details d'un etudiant
    public function show(Etudiant $etudiant)
    {
        $etudiant = $etudiant->load('user.role');
        return view('admin.etudiants.show', compact('etudiant'));
    }

    // Affiche le formulaire pour modifier un etudiant
    public function edit(Etudiant $etudiant)
    {
        $etudiant = $etudiant->load('user.role');
        return view('admin.etudiants.edit', compact('etudiant'));
    }

    // Met a jour les infos d'un etudiant
    public function update(Request $request, Etudiant $gestion_etudiant)
    {
        $request->validate([
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
        ]);

        // Mise a jour des donnees
        $gestion_etudiant->update([
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('etudiants.index')->with('success', 'Etudiant modifie avec succes.');
    }

    // Supprime les infos d'un etudiant
    public function destroy(Etudiant $etudiant)
    {
        // On supprime uniquement les infos liees a la table etudiants
        $etudiant->delete();

        return redirect()->route('etudiants.index')->with('success', 'Etudiant supprime avec succes.');
    }
}

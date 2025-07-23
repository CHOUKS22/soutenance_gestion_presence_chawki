<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coordinateur;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class CoordinateurController extends Controller
{

    public function index()
    {
        //On recupere les coordinateurs avec leur utilisateur, trier par date de création
        $coordinateurs = Coordinateur::with('user')->latest()->paginate();

        //On recupere mes utilisateurs avec le role coordinateur
        $roleCoordinateur = Role::where('nom', 'Coordinateur')->first();
        $usersCoordinateurs = [];

        if ($roleCoordinateur) {
            $usersCoordinateurs = User::where('role_id', $roleCoordinateur->id)
                ->whereNotIn('id', Coordinateur::pluck('user_id'))
                ->get();
        }

        return view('admin.coordinateurs.index', compact('coordinateurs', 'usersCoordinateurs'));
    }


    public function create()
    {
        //On recupere les coordinateurs avec leur utilisateur, trié par date de création
        $coordinateurs = Coordinateur::with('user')->latest()->paginate();

        //On recupere mes utilisateurs avec le role coordinateur
        $roleCoordinateur = Role::where('nom', 'Coordinateur')->first();
        $usersCoordinateurs = [];

        if ($roleCoordinateur) {
            $usersCoordinateurs = User::where('role_id', $roleCoordinateur->id)
                ->whereNotIn('id', Coordinateur::pluck('user_id'))
                ->get();
        }

        return view('admin.coordinateurs.create', compact('coordinateurs', 'usersCoordinateurs'));
    }


    public function store(Request $request)
    {
        // On valide les champs
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:coordinateur pédagogique,coordinateur de filière',
        ]);

        // On vérifie qu'il n'existe pas deja un coordinateur avec ce user_id
        if (Coordinateur::where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'Ce coordinateur existe deja.');
        }

        // On enregistre le nouveau coordinateur
        Coordinateur::create($request->only(['user_id', 'role']));

        return redirect()->route('coordinateurs.index')->with('success', 'Coordinateur ajouté avec succès.');
    }


    public function show(Coordinateur $coordinateur)
    {
        // On récupère le coordinateur avec son user et son rôle
        $coordinateur = $coordinateur->load('user.role');
        return view('admin.coordinateurs.show', compact('coordinateur'));
    }

    public function edit(Coordinateur $coordinateur)
    {
        $coordinateur->load('user.role'); 
        return view('admin.coordinateurs.edit', compact('coordinateur'));
    }


    public function update(Request $request, Coordinateur $coordinateur)
    {
        // On valide le champ role
        $request->validate([
            'role' => 'required|in:coordinateur pédagogique,coordinateur de filière',
        ]);

        // Mise à jour des infos
        $coordinateur->update(['role' => $request->role]);

        return redirect()->route('coordinateurs.show', $coordinateur)->with('success', 'Coordinateur modifié avec succès.');
    }

    public function destroy(Coordinateur $coordinateur)
    {
        // On supprime uniquement la fiche coordinateur (le user reste actif)
        $coordinateur->delete();

        return redirect()->route('coordinateurs.index')->with('success', 'Coordinateur supprimé avec succès.');
    }
}

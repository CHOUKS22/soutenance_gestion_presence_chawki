<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\AnneeClasse;
use App\Models\Etudiant;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesseurEtudiantController extends Controller
{
     public function index()
    {
        $professeur = Auth::user()->professeur;

        // Récupère les ID des classes où le professeur a des séances
        $classeIds = Seance::where('professeur_id', $professeur->id)
            ->pluck('classe_id')
            ->unique()
            ->toArray();

        // Récupère les année_classe associées à ces classes
        $anneeClasseIds = AnneeClasse::whereIn('classe_id', $classeIds)
            ->pluck('id');

        // Récupère les étudiants + leur classe via les jointures
        $etudiants = \App\Models\Etudiant::whereIn('id', function ($query) use ($anneeClasseIds) {
                $query->select('etudiant_id')
                      ->from('annee_classe_etudiant')
                      ->whereIn('annee_classe_id', $anneeClasseIds);
            })
            ->with(['user', 'anneeClasses.classe']) // Charge aussi les classes
            ->get();

        return view('professeur.etudiants.index', compact('etudiants'));
    }
}

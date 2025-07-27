<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Illuminate\Support\Facades\Auth;

class SeanceProfesseurController extends Controller
{
    /**
     * Affiche la liste des séances du professeur connecté.
     */
    public function index()
    {
        $professeur = Auth::user()->professeur;

        if (!$professeur) {
            abort(403, 'Accès refusé : vous n\'êtes pas un professeur.');
        }

        $seances = Seance::with(['classe', 'matiere', 'typeSeance'])
            ->where('professeur_id', $professeur->id)
            ->orderByDesc('date_debut')
            ->paginate(10);

        return view('professeur.seances.index', compact('seances'));
    }
}

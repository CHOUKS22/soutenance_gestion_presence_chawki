<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmploiDuTempsProfesseurController extends Controller
{
    public function index(Request $request)
    {
        // Recuperer l'id du professeur connecte
        $professeurId = Auth::user()->professeur->id;

        // Recuperer les seances du professeur avec les relations necessaires
        $seancesQuery = Seance::with([
            'anneeClasse.classe',
            'matiere',
            'typeSeance'
        ])->where('professeur_id', $professeurId);

        // Si une semaine est selectionnee via le formulaire
        if ($request->filled('semaine')) {
            // On calcule les dates de debut et fin de la semaine
            $startOfWeek = Carbon::parse($request->semaine)->startOfWeek();
            $endOfWeek = Carbon::parse($request->semaine)->endOfWeek();

            // On filtre les seances entre ces deux dates
            $seancesQuery->whereBetween('date_debut', [$startOfWeek, $endOfWeek]);
        }

        // Recuperer les seances triees par date
        $seances = $seancesQuery->orderBy('date_debut', 'asc')->get();

        // Retourner la vue avec les seances du professeur
        return view('professeur.emploi_du_temps.index', compact('seances'));
    }
}

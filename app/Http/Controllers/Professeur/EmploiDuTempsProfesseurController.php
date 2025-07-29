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
        $professeurId = Auth::user()->professeur->id;

        // On récupère toutes les séances du professeur
        $seancesQuery = Seance::with(['anneeClasse.classe', 'matiere', 'typeSeance'])
            ->where('professeur_id', $professeurId);

        // Si l'utilisateur filtre par semaine
        if ($request->filled('semaine')) {
            // On récupère les dates de début et de fin de la semaine
            $startOfWeek = Carbon::parse($request->semaine)->startOfWeek();
            $endOfWeek = Carbon::parse($request->semaine)->endOfWeek();

            $seancesQuery->whereBetween('date_debut', [$startOfWeek, $endOfWeek]);
        }

        // Trier les séances par date croissante
        $seances = $seancesQuery->orderBy('date_debut', 'asc')->get();

        return view('professeur.emploi_du_temps.index', compact('seances'));
    }
}

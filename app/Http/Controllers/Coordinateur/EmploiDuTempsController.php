<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Seance;
use App\Models\AnneeClasse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('fr');

        // ID du coordinateur connecte
        $coordinateurId = Auth::user()->coordinateur->id;

        // Liste des annees/classes liees au coordinateur
        $anneesClasses = AnneeClasse::with('classe')
            ->where('coordinateur_id', $coordinateurId)
            ->get();

        // Recuperer les classes disponibles
        $classes = $anneesClasses->pluck('classe')->unique('id');

        // Date de la semaine (aujourd'hui ou selection)
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

        // ID de la classe selectionnee
        $selectedClasseId = $request->input('classe_id') ?? ($classes->first()->id ?? null);

        // ID de l'entree annee_classe correspondante
        $anneeClasseId = $anneesClasses
            ->firstWhere('classe_id', $selectedClasseId)?->id;

        // Recuperer les seances de la semaine pour cette classe
        $seances = Seance::with(['matiere', 'typeSeance', 'professeur.user', 'anneeClasse.classe'])
            ->whereBetween('date_debut', [$startOfWeek, $endOfWeek])
            ->when($anneeClasseId, function ($query) use ($anneeClasseId) {
                return $query->where('annee_classe_id', $anneeClasseId);
            })
            ->orderBy('date_debut')
            ->get();

        // Envoyer les donnees a la vue
        return view('coordinateur.emploie', compact(
            'seances',
            'classes',
            'selectedClasseId',
            'startOfWeek',
            'endOfWeek'
        ));
    }
}

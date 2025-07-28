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

        $coordinateurId = Auth::user()->coordinateur->id;

        // Récupération des associations année-classe du coordinateur
        $anneesClasses = AnneeClasse::with('classe')
            ->where('coordinateur_id', $coordinateurId)
            ->get();

        // Liste des classes disponibles (pour le select)
        $classes = $anneesClasses->pluck('classe')->unique('id');

        // Semaine courante ou sélectionnée
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

        // ID de la classe sélectionnée
        $selectedClasseId = $request->input('classe_id') ?? ($classes->first()->id ?? null);

        // Récupérer l'annee_classe_id correspondant à la classe sélectionnée
        $anneeClasseId = $anneesClasses
            ->firstWhere('classe_id', $selectedClasseId)?->id;

        // Récupération des séances pour cette semaine et cette année-classe
        $seances = Seance::with(['matiere', 'typeSeance', 'professeur.user', 'anneeClasse.classe'])
            ->whereBetween('date_debut', [$startOfWeek, $endOfWeek])
            ->when($anneeClasseId, function ($query) use ($anneeClasseId) {
                return $query->where('annee_classe_id', $anneeClasseId);
            })
            ->orderBy('date_debut')
            ->get();

        return view('coordinateur.emploie', compact(
            'seances',
            'classes',
            'selectedClasseId',
            'startOfWeek',
            'endOfWeek'
        ));
    }
}

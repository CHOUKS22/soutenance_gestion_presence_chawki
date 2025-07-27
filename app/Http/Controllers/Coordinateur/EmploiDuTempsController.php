<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Seance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        // Important : forcer la langue en français pour Carbon
        Carbon::setLocale('fr');

        $coordinateurId = Auth::user()->coordinateur->id;

        // Récupérer toutes les classes gérées par ce coordinateur
        $classes = Classe::whereHas('anneesClasses', function ($query) use ($coordinateurId) {
            $query->where('coordinateur_id', $coordinateurId);
        })->get();

        // Semaine courante ou sélectionnée
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

        // Classe sélectionnée (par défaut : la première)
        $selectedClasseId = $request->input('classe_id') ?? ($classes->first()->id ?? null);

        // Récupération des séances pour la semaine et la classe sélectionnée
        $seances = Seance::with(['matiere', 'classe', 'typeSeance', 'professeur.user'])
            ->whereBetween('date_debut', [$startOfWeek, $endOfWeek])
            ->when($selectedClasseId, function ($query, $selectedClasseId) {
                return $query->where('classe_id', $selectedClasseId);
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

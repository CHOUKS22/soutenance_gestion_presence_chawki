<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Presence;
use App\Models\Seance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardProfesseurController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $professeurId = $user->professeur->id;

        // Total séances
        $totalSeances = Seance::where('professeur_id', $professeurId)->count();

        // Séances à venir cette semaine
        $seancesAVenir = Seance::where('professeur_id', $professeurId)
            ->whereBetween('date_debut', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Absences non justifiées
        $absencesNonJustifiees = Absence::whereHas('seance', function ($query) use ($professeurId) {
            $query->where('professeur_id', $professeurId);
        })->whereDoesntHave('justifications')->count();

        // Présences totales et absences totales
        $totalPresences = Presence::whereHas('seance', function ($query) use ($professeurId) {
            $query->where('professeur_id', $professeurId);
        })->count();

        $totalAbsences = Absence::whereHas('seance', function ($query) use ($professeurId) {
            $query->where('professeur_id', $professeurId);
        })->count();

        $tauxPresence = $totalPresences + $totalAbsences > 0
            ? round(($totalPresences / ($totalPresences + $totalAbsences)) * 100)
            : 100;

        // Moyenne séances / semaine
        $premiereSeance = Seance::where('professeur_id', $professeurId)->orderBy('date_debut')->first();
        $nombreSemaines = $premiereSeance
            ? max(1, now()->diffInWeeks(Carbon::parse($premiereSeance->date_debut)))
            : 1;

        $moyenneParSemaine = round($totalSeances / $nombreSemaines, 1);

        // Durée moyenne des cours
        $seances = Seance::where('professeur_id', $professeurId)->get();
        $totalMinutes = 0;

        foreach ($seances as $seance) {
            $totalMinutes += Carbon::parse($seance->date_debut)->diffInMinutes(Carbon::parse($seance->date_fin));
        }

        $dureeMoyenne = count($seances) > 0
            ? gmdate("H\h i\m", ($totalMinutes / count($seances)) * 60)
            : '0h 0m';

        return view('professeur.dashboard', compact(
            'user',
            'totalSeances',
            'seancesAVenir',
            'absencesNonJustifiees',
            'tauxPresence',
            'moyenneParSemaine',
            'dureeMoyenne'
        ));
    }

    public function absencesNonJustifiees()
    {
        $professeurId = Auth::user()->professeur->id;

        $absences = Absence::with(['etudiant', 'seance.matiere', 'seance.anneeClasse.classe'])
            ->whereHas('seance', function ($query) use ($professeurId) {
                $query->where('professeur_id', $professeurId);
            })
            ->whereDoesntHave('justifications')
            ->orderByDesc('created_at')
            ->get();

        return view('professeur.absences', compact('absences'));
    }
}

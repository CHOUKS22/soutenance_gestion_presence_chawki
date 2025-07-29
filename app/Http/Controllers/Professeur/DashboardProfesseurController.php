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
        // Recuperer le professeur connecte
        $user = Auth::user();
        $professeurId = $user->professeur->id;

        // Nombre total de seances animees par le professeur
        $totalSeances = Seance::where('professeur_id', $professeurId)->count();

        // Nombre de seances prevues cette semaine
        $seancesAVenir = Seance::where('professeur_id', $professeurId)
            ->whereBetween('date_debut', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Nombre total d'absences non justifiees dans ses seances
        $absencesNonJustifiees = Absence::whereHas('seance', function ($query) use ($professeurId) {
            $query->where('professeur_id', $professeurId);
        })->whereDoesntHave('justifications')->count();

        // Nombre total de presences enregistrees dans ses seances
        $totalPresences = Presence::whereHas('seance', function ($query) use ($professeurId) {
            $query->where('professeur_id', $professeurId);
        })->count();

        // Nombre total d'absences (justifiees ou non)
        $totalAbsences = Absence::whereHas('seance', function ($query) use ($professeurId) {
            $query->where('professeur_id', $professeurId);
        })->count();

        // Calcul du taux de presence (en pourcentage)
        $tauxPresence = $totalPresences + $totalAbsences > 0
            ? round(($totalPresences / ($totalPresences + $totalAbsences)) * 100)
            : 100;

        // Recuperer la premiere seance pour calculer la duree d'activite
        $premiereSeance = Seance::where('professeur_id', $professeurId)->orderBy('date_debut')->first();

        // Calcul du nombre de semaines d'activite
        $nombreSemaines = $premiereSeance
            ? max(1, now()->diffInWeeks(Carbon::parse($premiereSeance->date_debut)))
            : 1;

        // Moyenne de seances par semaine
        $moyenneParSemaine = round($totalSeances / $nombreSemaines, 1);

        // Calcul de la duree moyenne d'une seance (en minutes)
        $seances = Seance::where('professeur_id', $professeurId)->get();
        $totalMinutes = 0;

        foreach ($seances as $seance) {
            $totalMinutes += Carbon::parse($seance->date_debut)->diffInMinutes(Carbon::parse($seance->date_fin));
        }

        // Formater la duree moyenne en Hh Mm
        $dureeMoyenne = count($seances) > 0
            ? gmdate("H\h i\m", ($totalMinutes / count($seances)) * 60)
            : '0h 0m';

        // Afficher la vue du dashboard professeur avec les donnees calculees
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
        // Recuperer l'identifiant du professeur connecte
        $professeurId = Auth::user()->professeur->id;

        // Recuperer les absences non justifiees des etudiants dans ses seances
        $absences = Absence::with(['etudiant', 'seance.matiere', 'seance.anneeClasse.classe'])
            ->whereHas('seance', function ($query) use ($professeurId) {
                $query->where('professeur_id', $professeurId);
            })
            ->whereDoesntHave('justifications')
            ->orderByDesc('created_at')
            ->get();

        // Retourner la vue avec la liste des absences non justifiees
        return view('professeur.absences', compact('absences'));
    }
}

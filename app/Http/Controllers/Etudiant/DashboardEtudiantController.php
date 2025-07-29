<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Presence;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardEtudiantController extends Controller
{
    public function index(Request $request)
    {
        // Recuperer l'etudiant connecte
        $etudiant = Auth::user()->etudiant;

        // Verifier que l'utilisateur est bien un etudiant
        if (!$etudiant) {
            abort(403, 'Acces non autorise');
        }

        $etudiantId = $etudiant->id;

        // Gestion de la semaine choisie pour filtrer l'emploi du temps
        $semaineInput = $request->input('semaine');

        if ($semaineInput) {
            try {
                // Tenter de parser la semaine fournie
                $dateDebut = \Carbon\Carbon::createFromFormat('Y-\WW', $semaineInput)->startOfWeek();
            } catch (\Exception $e) {
                // Si erreur, on prend la semaine en cours
                $dateDebut = now()->startOfWeek();
            }
        } else {
            // Si aucune semaine donnee, on prend la semaine actuelle
            $dateDebut = now()->startOfWeek();
        }

        $dateFin = $dateDebut->copy()->endOfWeek();

        // Recuperer les seances de l'etudiant pour la semaine
        $seances = Seance::whereHas('anneeClasse.etudiants', function ($query) use ($etudiantId) {
                $query->where('etudiants.id', $etudiantId);
            })
            ->whereBetween('date_debut', [$dateDebut, $dateFin])
            ->orderBy('date_debut', 'asc')
            ->with([
                'matiere',
                'anneeClasse.classe',
                'typeSeance',
                'professeur.user'
            ])
            ->get();

        // Recuperer les presences de l'etudiant
        $presences = Presence::with(['seance.matiere', 'statutPresence'])
            ->where('etudiant_id', $etudiantId)
            ->paginate(10);

        // Recuperer les absences de l'etudiant
        $absences = Absence::with(['seance.matiere', 'justifications'])
            ->where('etudiant_id', $etudiantId)
            ->paginate(10);

        // Filtrer les absences justifiees
        $absencesJustifiees = $absences->filter(function ($a) {
            return $a->justifications->isNotEmpty();
        });

        // Filtrer les absences non justifiees
        $absencesNonJustifiees = $absences->filter(function ($a) {
            return $a->justifications->isEmpty();
        });

        // Calculer la note d'assiduite sur 20
        $nbPresences = $presences->count();
        $nbAbsences = $absences->count();
        $total = $nbPresences + $nbAbsences;

        $noteAssiduite = $total > 0 ? round(($nbPresences / $total) * 20, 2) : null;

        // Retourner la vue du tableau de bord avec les donnees
        return view('etudiant.dashboard', compact(
            'etudiant',
            'seances',
            'presences',
            'absences',
            'absencesJustifiees',
            'absencesNonJustifiees',
            'noteAssiduite'
        ));
    }
}

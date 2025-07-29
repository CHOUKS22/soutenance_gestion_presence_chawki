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
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant) {
            abort(403, 'Accès non autorisé');
        }

        $etudiantId = $etudiant->id;

        // Gestion de la semaine sélectionnée
        $semaineInput = $request->input('semaine');
        if ($semaineInput) {
            try {
                $dateDebut = \Carbon\Carbon::createFromFormat('Y-\WW', $semaineInput)->startOfWeek();
            } catch (\Exception $e) {
                $dateDebut = now()->startOfWeek();
            }
        } else {
            $dateDebut = now()->startOfWeek();
        }
        $dateFin = $dateDebut->copy()->endOfWeek();

        // Emploi du temps filtré par semaine
        $seances = Seance::whereHas('anneeClasse.etudiants', function ($query) use ($etudiantId) {
            $query->where('etudiants.id', $etudiantId);
        })
            ->whereBetween('date_debut', [$dateDebut, $dateFin])
            ->orderBy('date_debut', 'asc')
            ->with(['matiere', 'anneeClasse.classe', 'typeSeance', 'professeur.user'])
            ->get();

        // Présences
        $presences = Presence::with(['seance.matiere', 'statutPresence'])
            ->where('etudiant_id', $etudiantId)
            ->paginate(10);

        // Absences
        $absences = Absence::with(['seance.matiere', 'justifications'])
            ->where('etudiant_id', $etudiantId)
            ->paginate(10);

        $absencesJustifiees = $absences->filter(function ($a) {
            return $a->justifications->isNotEmpty();
        });

        $absencesNonJustifiees = $absences->filter(function ($a) {
            return $a->justifications->isEmpty();
        });

        // Note d’assiduité (/20)
        $nbPresences = $presences->count();
        $nbAbsences = $absences->count();
        $total = $nbPresences + $nbAbsences;

        $noteAssiduite = $total > 0 ? round(($nbPresences / $total) * 20, 2) : null;

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

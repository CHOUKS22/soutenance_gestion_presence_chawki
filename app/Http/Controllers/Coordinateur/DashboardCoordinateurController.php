<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Statut_seance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardCoordinateurController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $coordinateur = $user->coordinateur;

        $anneesClasses = $coordinateur->anneesClasses()->with(['classe', 'anneeAcademique'])->get();

        $classeIds = $anneesClasses->pluck('classe_id')->unique();
        $anneeClasseIds = $anneesClasses->pluck('id');

        $totalClasses = $classeIds->count();

        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        $totalSeances = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->count();

        $matiereIds = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->pluck('matiere_id')
            ->unique();

        $totalMatieres = $matiereIds->count();

        $totalEtudiants = DB::table('annee_classe_etudiant')
            ->whereIn('annee_classe_id', $anneeClasseIds)
            ->distinct('etudiant_id')
            ->count();

        $seancesRecentes = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->with(['anneeClasse.classe', 'matiere', 'professeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $matieresRecentes = Matiere::whereIn('id', $matiereIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $seancesAujourdhui = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->whereDate('date_debut', now()->toDateString())
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->with(['anneeClasse.classe', 'matiere'])
            ->orderBy('date_debut')
            ->get();

        $seanceIds = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->pluck('id');

        $nombrePresences = Presence::whereIn('seance_id', $seanceIds)->count();
        $nombreAbsences = Absence::whereIn('seance_id', $seanceIds)->count();

        $totalEnregistrements = $nombrePresences + $nombreAbsences;

        $tauxPresence = $totalEnregistrements > 0
            ? round(($nombrePresences / $totalEnregistrements) * 100, 1)
            : 0;

        $labels = [];
        $data = [];

        $classes = Classe::whereIn('id', $classeIds)
            ->with('anneesClasses.seances')
            ->get();

        foreach ($classes as $classe) {
            $totalClassePresences = 0;
            $totalClasseAbsences = 0;

            foreach ($classe->anneesClasses as $ac) {
                foreach ($ac->seances as $seance) {
                    if ($seance->statut_seance_id == $statutAnnuleeId) continue;

                    $totalClassePresences += $seance->presences()->count();
                    $totalClasseAbsences += $seance->absences()->count();
                }
            }

            $totalClasse = $totalClassePresences + $totalClasseAbsences;

            $tauxClasse = $totalClasse > 0
                ? round(($totalClassePresences / $totalClasse) * 100, 2)
                : 0;

            $labels[] = $classe->nom;
            $data[] = $tauxClasse;
        }

        return view('coordinateur.dashboard', [
            'user' => $user,
            'totalMatieres' => $totalMatieres,
            'totalSeances' => $totalSeances,
            'totalClasses' => $totalClasses,
            'totalEtudiants' => $totalEtudiants,
            'seancesRecentes' => $seancesRecentes,
            'matieresRecentes' => $matieresRecentes,
            'seancesAujourdhui' => $seancesAujourdhui,
            'tauxPresence' => $tauxPresence,
            'chartLabels' => json_encode($labels),
            'chartData' => json_encode($data),
        ]);
    }
}

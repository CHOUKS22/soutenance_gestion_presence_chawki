<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Statut_presence;
use App\Models\Statut_seance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardCoordinateurController extends Controller
{
    public function index()
    {
        // Utilisateur connecte
        $user = Auth::user();
        $coordinateur = $user->coordinateur;

        // Recuperer les annees/classes du coordinateur
        $anneesClasses = $coordinateur->anneesClasses()->with(['classe', 'anneeAcademique'])->get();

        // IDs des classes et annees_classes
        $classeIds = $anneesClasses->pluck('classe_id')->unique();
        $anneeClasseIds = $anneesClasses->pluck('id');

        // Nombre total de classes
        $totalClasses = $classeIds->count();

        // ID du statut "Annulee"
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Nombre total de seances (hors annulees)
        $totalSeances = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->count();

        // Recuperer les matieres utilisees dans les seances valides
        $matiereIds = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->pluck('matiere_id')
            ->unique();

        // Nombre de matieres
        $totalMatieres = $matiereIds->count();

        // Nombre d'etudiants inscrits
        $totalEtudiants = DB::table('annee_classe_etudiant')
            ->whereIn('annee_classe_id', $anneeClasseIds)
            ->distinct('etudiant_id')
            ->count();

        // Dernieres seances ajoutees
        $seancesRecentes = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->with(['anneeClasse.classe', 'matiere', 'professeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Dernieres matieres ajoutees
        $matieresRecentes = Matiere::whereIn('id', $matiereIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Seances du jour
        $seancesAujourdhui = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->whereDate('date_debut', now()->toDateString())
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->with(['anneeClasse.classe', 'matiere'])
            ->orderBy('date_debut')
            ->get();

        // Recuperer toutes les seances valides
        $seanceIds = Seance::whereIn('annee_classe_id', $anneeClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId)
            ->pluck('id');

        // Comptage des presences et absences
        $nombrePresences = Presence::whereIn('seance_id', $seanceIds)->count();
        $nombreAbsences = Absence::whereIn('seance_id', $seanceIds)->count();

        // Total pour le calcul du taux
        $totalEnregistrements = $nombrePresences + $nombreAbsences;

        // Taux de presence global
        $tauxPresence = $totalEnregistrements > 0
            ? round(($nombrePresences / $totalEnregistrements) * 100, 1)
            : 0;

        // Donnees pour le graphique
        $labels = [];
        $data = [];

        // Recuperer les classes avec leurs seances
        $classes = Classe::whereIn('id', $classeIds)
            ->with('anneesClasses.seances')
            ->get();

        // Calcul du taux de chaque classe
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

        // Liste des etudiants droppés (taux de presence <= 30%)
        $etudiants = Etudiant::with('user')->get();
        $matieres = Matiere::whereIn('id', $matiereIds)->get();
        $droppages = [];

        foreach ($etudiants as $etudiant) {
            foreach ($matieres as $matiere) {
                // Recuperer les seances valides de cette matiere
                $seancesMatiere = Seance::where('matiere_id', $matiere->id)
                    ->whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->pluck('id');

                if ($seancesMatiere->isEmpty()) continue;

                // Compter les presences
                $nbPresences = Presence::whereIn('seance_id', $seancesMatiere)
                    ->where('etudiant_id', $etudiant->id)
                    ->count();

                // Compter uniquement les absences non justifiees
                $nbAbsences = Absence::whereIn('seance_id', $seancesMatiere)
                    ->where('etudiant_id', $etudiant->id)
                    ->whereDoesntHave('justifications')
                    ->count();


                $total = $nbPresences + $nbAbsences;
                if ($total === 0) continue;

                $taux = ($nbPresences / $total) * 100;

                if ($taux <= 30) {
                    $droppages[] = [
                        'etudiant' => $etudiant,
                        'matiere' => $matiere,
                        'taux' => $taux,
                    ];
                }
            }
        }

        // Envoyer les donnees a la vue
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
            'droppages' => $droppages
        ]);
    }
}

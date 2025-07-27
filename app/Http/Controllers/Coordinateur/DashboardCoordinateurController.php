<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Presence;
use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardCoordinateurController extends Controller
{
    public function index()
    {
        // On recupere l'utilisateur connecte (coordinateur)
        $user = Auth::user();
        $coordinateur = $user->coordinateur;

        // On recupere toutes les associations annee-classe du coordinateur avec les relations
        $anneesClasses = $coordinateur->anneesClasses()->with(['classe', 'anneeAcademique'])->get();

        // On extrait les IDs des classes de ces associations
        $classeIds = $anneesClasses->pluck('classe_id')->unique();
        $anneeClasseIds = $anneesClasses->pluck('id');

        // Calcul du total de classes que gere le coordinateur
        $totalClasses = $classeIds->count();

        // Calcul du nombre total de seances pour ces classes
        $totalSeances = Seance::whereIn('classe_id', $classeIds)->count();

        // Recuperation des IDs des matieres via les seances
        $matiereIds = Seance::whereIn('classe_id', $classeIds)->pluck('matiere_id')->unique();

        // Calcul du nombre total de matieres
        $totalMatieres = $matiereIds->count();

        // Calcul du nombre total d'etudiants lies aux classes du coordinateur
        $totalEtudiants = DB::table('annee_classe_etudiant')
            ->whereIn('annee_classe_id', $anneeClasseIds)
            ->distinct('etudiant_id')
            ->count();

        // Recuperation des 5 dernieres seances ajoutees
        $seancesRecentes = Seance::whereIn('classe_id', $classeIds)
            ->with(['classe', 'matiere', 'professeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recuperation des 5 dernieres matieres (par les IDs trouves plus haut)
        $matieresRecentes = Matiere::whereIn('id', $matiereIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recuperation des seances qui ont lieu aujourd'hui
        $seancesAujourdhui = Seance::whereIn('classe_id', $classeIds)
            ->whereDate('date_debut', now()->toDateString())
            ->with(['classe', 'matiere'])
            ->orderBy('date_debut')
            ->get();

        // Recuperation des IDs des seances pour ces classes
        $seanceIds = Seance::whereIn('classe_id', $classeIds)->pluck('id');

        // Nouveau calcul avec table separate : presences + absences
        $nombrePresences = Presence::whereIn('seance_id', $seanceIds)->count();
        $nombreAbsences = Absence::whereIn('seance_id', $seanceIds)->count();

        $totalEnregistrements = $nombrePresences + $nombreAbsences;

        // Calcul du taux global de presence (presences / total)
        $tauxPresence = $totalEnregistrements > 0
            ? round(($nombrePresences / $totalEnregistrements) * 100, 1)
            : 0;

        // --- Partie graphique : taux de presence par classe ---

        $labels = []; // Noms des classes
        $data = [];   // Taux de chaque classe

        // On recupere les classes concernees avec leurs presences
        $classes = Classe::whereIn('id', $classeIds)
            ->with('seances.presences', 'seances.absences')
            ->get();

        // Pour chaque classe on calcule son taux de presence
        foreach ($classes as $classe) {
            $totalClassePresences = 0;
            $totalClasseAbsences = 0;

            foreach ($classe->seances as $seance) {
                $totalClassePresences += $seance->presences->count();
                $totalClasseAbsences += $seance->absences->count();
            }

            $totalClasse = $totalClassePresences + $totalClasseAbsences;

            $tauxClasse = $totalClasse > 0
                ? round(($totalClassePresences / $totalClasse) * 100, 2)
                : 0;

            $labels[] = $classe->nom;
            $data[] = $tauxClasse;
        }

        // On retourne les donnees vers la vue dashboard du coordinateur
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

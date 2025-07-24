<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardCoordinateurController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $coordinateur = $user->coordinateur;

        if (!$coordinateur) {
            // Si l'utilisateur n'est pas un coordinateur, on retourne des valeurs par défaut
            return view('coordinateur.dashboard', [
                'user' => $user,
                'totalMatieres' => 0,
                'totalSeances' => 0,
                'totalClasses' => 0,
                'totalEtudiants' => 0,
                'seancesAujourdhui' => collect(),
                'prochainesSeances' => collect(),
                'seancesRecentes' => collect(),
                'matieresRecentes' => collect(),
                'tauxPresence' => 0
            ]);
        }

        // Récupérer les années-classes que coordonne ce coordinateur
        $anneesClasses = $coordinateur->anneesClasses()->with(['classe', 'anneeAcademique'])->get();
        $classeIds = $anneesClasses->pluck('classe_id')->unique();
        $anneeClasseIds = $anneesClasses->pluck('id');

        // Statistiques liées aux classes du coordinateur
        $totalClasses = $classeIds->count();

        // Séances liées aux classes du coordinateur
        $totalSeances = Seance::whereIn('classe_id', $classeIds)->count();

        // Matières utilisées dans les séances des classes du coordinateur
        $matiereIds = Seance::whereIn('classe_id', $classeIds)->pluck('matieres_id')->unique();
        $totalMatieres = $matiereIds->count();        // Étudiants inscrits dans les classes du coordinateur
        $totalEtudiants = DB::table('annee_classe_etudiant')
            ->whereIn('annee_classe_id', $anneeClasseIds)
            ->distinct('etudiant_id')
            ->count();

        // Séances d'aujourd'hui pour les classes du coordinateur
        $seancesAujourdhui = Seance::whereDate('date_debut', Carbon::today())
            ->whereIn('classe_id', $classeIds)
            ->with(['classe', 'matiere', 'professeur'])
            ->orderBy('date_debut')
            ->get();

        // Prochaines séances (7 prochains jours) pour les classes du coordinateur
        $prochainesSeances = Seance::whereBetween('date_debut', [
                Carbon::tomorrow(),
                Carbon::today()->addDays(7)
            ])
            ->whereIn('classe_id', $classeIds)
            ->with(['classe', 'matiere', 'professeur'])
            ->orderBy('date_debut')
            ->limit(5)
            ->get();

        // Séances récentes pour les classes du coordinateur
        $seancesRecentes = Seance::whereIn('classe_id', $classeIds)
            ->with(['classe', 'matiere', 'professeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Matières utilisées dans les classes du coordinateur
        $matieresRecentes = Matiere::whereIn('id', $matiereIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Taux de présence pour les séances des classes du coordinateur
        $seanceIds = Seance::whereIn('classe_id', $classeIds)->pluck('id');
        $totalPresences = Presence::whereIn('seance_id', $seanceIds)->count();
        $presencesMarquees = 0;

        // Sécurité pour éviter les erreurs si la table StatutPresence est vide
        try {
            $presencesMarquees = Presence::whereIn('seance_id', $seanceIds)
                ->whereHas('statutPresence', function($query) {
                    $query->where('libelle', 'like', '%Présent%');
                })->count();
        } catch (\Exception $e) {
            // En cas d'erreur, on continue avec 0
        }

        $tauxPresence = $totalPresences > 0 ? round(($presencesMarquees / $totalPresences) * 100, 1) : 0;

        return view('coordinateur.dashboard', compact(
            'user',
            'totalMatieres',
            'totalSeances',
            'totalClasses',
            'totalEtudiants',
            'seancesAujourdhui',
            'prochainesSeances',
            'seancesRecentes',
            'matieresRecentes',
            'tauxPresence'
        ));
    }
}

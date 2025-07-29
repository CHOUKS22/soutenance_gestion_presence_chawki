<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AnneeClasse;
use App\Models\AnneeClasseEtudiant;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Presence;
use App\Models\Seance;
use App\Models\Statut_presence;
use App\Models\Statut_seance;
use App\Models\StatutPresence;
use App\Models\Type_seance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiquesPresenceController extends Controller
{
    //Taux d'assiduite d'etudiant par matiere
    public function assiduiteEtudiantParMatiere(Request $request)
    {
        // Recuperer tous les etudiants avec leur utilisateur associe
        $etudiants = Etudiant::with('user')->get();

        // Recuperer l'etudiant selectionne
        $etudiantId = $request->input('etudiant_id');
        $notes = [];

        if ($etudiantId) {
            // Recuperer les IDs des statuts consideres comme une presence (present ou en retard)
            $statutsPresence = Statut_presence::whereIn('libelle', ['Présent', 'En retard'])->pluck('id')->toArray();

            // Recuperer l'ID du statut "Annulee" pour les seances
            $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

            // Recuperer les annees/classes auxquelles appartient l'etudiant
            $anneeClasseIds = DB::table('annee_classe_etudiant')
                ->where('etudiant_id', $etudiantId)
                ->pluck('annee_classe_id')
                ->toArray();

            // Trouver les matieres pour lesquelles il y a eu des seances dans ces annees/classes
            $matiereIds = Seance::whereIn('annee_classe_id', $anneeClasseIds)
                ->where('statut_seance_id', '!=', $statutAnnuleeId)
                ->pluck('matiere_id')
                ->unique()
                ->toArray();

            // Recuperer les objets matieres concernes
            $matieres = Matiere::whereIn('id', $matiereIds)->get();

            foreach ($matieres as $matiere) {
                // Compter le nombre total de seances valides pour cette matiere
                $totalSeances = Seance::where('matiere_id', $matiere->id)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->whereIn('annee_classe_id', $anneeClasseIds)
                    ->count();

                // Compter le nombre de presences de l'etudiant pour cette matiere
                $nombrePresences = Presence::where('etudiant_id', $etudiantId)
                    ->whereIn('statuts_presence_id', $statutsPresence)
                    ->whereHas('seance', function ($query) use ($matiere, $statutAnnuleeId, $anneeClasseIds) {
                        $query->where('matiere_id', $matiere->id)
                            ->where('statut_seance_id', '!=', $statutAnnuleeId)
                            ->whereIn('annee_classe_id', $anneeClasseIds);
                    })
                    ->count();

                // Calculer la note sur 20 (regle de trois)
                $note = $totalSeances > 0 ? round(($nombrePresences / $totalSeances) * 20, 2) : 0;

                // Ajouter les resultats dans le tableau final
                $notes[] = [
                    'matiere' => $matiere->nom,
                    'total' => $totalSeances,
                    'presences' => $nombrePresences,
                    'note' => $note
                ];
            }
        }

        // Afficher la vue avec les resultats
        return view('coordinateur.assiduite', [
            'etudiants' => $etudiants,
            'notes' => $notes,
            'selectedEtudiantId' => $etudiantId,
        ]);
    }
    //Taux de presence par etudiant
    public function tauxPresenceEtudiant(Request $request)
    {
        // Recuperer tous les etudiants avec leur compte utilisateur
        $etudiants = Etudiant::with('user')->get();

        // Recuperer les IDs des statuts consideres comme presence
        $statuts = Statut_presence::whereIn('libelle', ['Présent', 'En retard'])->pluck('id');

        // Recuperer les donnees du formulaire
        $etudiantId = $request->etudiant_id;
        $periode = $request->periode;

        $dateDebut = null;
        $dateFin = null;

        // Choisir la periode selon le choix de l'utilisateur
        switch ($periode) {
            case 'semaine':
                $dateDebut = now()->startOfWeek();
                $dateFin = now()->endOfWeek();
                break;
            case 'semestre':
                $dateDebut = now()->month < 7 ? now()->startOfYear() : now()->startOfYear()->addMonths(6);
                $dateFin = $dateDebut->copy()->addMonths(6)->subDay();
                break;
            case 'annee':
                $dateDebut = now()->startOfYear();
                $dateFin = now()->endOfYear();
                break;
            case 'personnalisee':
                $dateDebut = Carbon::parse($request->date_debut);
                $dateFin = Carbon::parse($request->date_fin);
                break;
        }

        $totalSeances = 0;
        $totalPresences = 0;
        $taux = null;

        // Si tout est bien rempli, on calcule les donnees
        if ($etudiantId && $dateDebut && $dateFin) {
            // Recuperer l'ID du statut "Annulee"
            $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

            // Nombre total de seances valides auxquelles l'etudiant devait participer
            $totalSeances = Seance::whereBetween('date_debut', [$dateDebut, $dateFin])
                ->where('statut_seance_id', '!=', $statutAnnuleeId)
                ->whereHas('anneeClasse.etudiants', function ($query) use ($etudiantId) {
                    $query->where('etudiants.id', $etudiantId);
                })
                ->count();

            // Nombre de presences marquees pour cet etudiant pendant la periode
            $totalPresences = Presence::where('etudiant_id', $etudiantId)
                ->whereIn('statuts_presence_id', $statuts)
                ->whereHas('seance', function ($query) use ($dateDebut, $dateFin, $statutAnnuleeId) {
                    $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                        ->where('statut_seance_id', '!=', $statutAnnuleeId);
                })
                ->count();

            // Calcul du taux de presence
            $taux = $totalSeances > 0 ? round(($totalPresences / $totalSeances) * 100, 2) : null;
        }

        // Afficher les resultats dans la vue
        return view('coordinateur.taux-presence-etudiant', compact(
            'etudiants',
            'etudiantId',
            'periode',
            'dateDebut',
            'dateFin',
            'totalSeances',
            'totalPresences',
            'taux'
        ));
    }
    //Afficher le formulaire de selection de classe
    public function selectionClasse()
    {
        // Recuperer toutes les classes disponibles
        $classes = Classe::all();

        // Afficher la vue avec les classes et des donnees vides par defaut
        return view('coordinateur.presences.selection', [
            'classes' => $classes,
            'donnees' => collect(),
            'classe' => null
        ]);
    }
    // Afficher les statistiques de presence par classe
    public function graphiquePresence(Request $request, Classe $classe)
    {
        // Recuperer l'ID du statut "Annulee"
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Recuperer la periode de filtrage si elle est definie
        $dateDebut = $request->input('date_debut') ? Carbon::parse($request->input('date_debut'))->startOfDay() : null;
        $dateFin = $request->input('date_fin') ? Carbon::parse($request->input('date_fin'))->endOfDay() : null;

        // Recuperer les annees/classes associees a la classe selectionnee
        $anneesClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

        // Recuperer les etudiants inscrits dans ces annees/classes
        $etudiants = DB::table('annee_classe_etudiant')
            ->whereIn('annee_classe_id', $anneesClasseIds)
            ->join('etudiants', 'etudiants.id', '=', 'annee_classe_etudiant.etudiant_id')
            ->join('users', 'users.id', '=', 'etudiants.user_id')
            ->select('etudiants.id as etudiant_id', DB::raw("CONCAT(users.prenom, ' ', users.nom) as nom_complet"))
            ->get();

        // Recuperer les seances non annulees de cette classe
        $seancesQuery = Seance::whereIn('annee_classe_id', $anneesClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId);

        // Appliquer le filtre de date si present
        if ($dateDebut && $dateFin) {
            $seancesQuery->whereBetween('date_debut', [$dateDebut, $dateFin]);
        }

        $seances = $seancesQuery->get();
        $seanceIds = $seances->pluck('id');

        // Calculer le taux de presence pour chaque etudiant
        $donnees = $etudiants->map(function ($etudiant) use ($seanceIds) {
            $nbPresences = Presence::where('etudiant_id', $etudiant->etudiant_id)
                ->whereIn('seance_id', $seanceIds)
                ->count();

            $nbAbsences = Absence::where('etudiant_id', $etudiant->etudiant_id)
                ->whereIn('seance_id', $seanceIds)
                ->count();

            $total = $nbPresences + $nbAbsences;

            // Calcul du pourcentage de presence
            $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : 0;

            return (object)[
                'nom' => $etudiant->nom_complet,
                'taux' => $taux
            ];
        })->sortByDesc('taux')->values();

        // Retourner la vue avec les donnees pour le graphique
        return view('coordinateur.presences.graphique', [
            'classe' => $classe,
            'donnees' => $donnees,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
    }
    // Taux de presence par classe
    public function tauxPresenceParClasse(Request $request)
    {
        // Recuperer l'ID du statut "Annulee"
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Recuperer les dates de filtrage si elles existent
        $dateDebut = $request->input('date_debut') ? Carbon::parse($request->input('date_debut'))->startOfDay() : null;
        $dateFin = $request->input('date_fin') ? Carbon::parse($request->input('date_fin'))->endOfDay() : null;

        $classes = Classe::all();
        $donnees = [];

        // Parcourir chaque classe pour calculer le taux de presence
        foreach ($classes as $classe) {
            // Recuperer les annees/classes liees a la classe
            $anneesClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

            // Recuperer les seances valides
            $seancesQuery = Seance::whereIn('annee_classe_id', $anneesClasseIds)
                ->where('statut_seance_id', '!=', $statutAnnuleeId);

            if ($dateDebut && $dateFin) {
                $seancesQuery->whereBetween('date_debut', [$dateDebut, $dateFin]);
            }

            $seanceIds = $seancesQuery->pluck('id');

            // Compter les presences et absences dans ces seances
            $nbPresences = Presence::whereIn('seance_id', $seanceIds)->count();
            $nbAbsences = Absence::whereIn('seance_id', $seanceIds)->count();
            $total = $nbPresences + $nbAbsences;

            // Calcul du taux de presence global
            $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : 0;

            // Ajouter les donnees de la classe
            $donnees[] = (object)[
                'classe' => $classe->nom,
                'taux' => $taux
            ];
        }

        // Trier les classes par taux de presence en ordre decroissant
        usort($donnees, fn($a, $b) => $b->taux <=> $a->taux);

        // Afficher la vue avec les donnees
        return view('coordinateur.presences.parClasse', [
            'donnees' => $donnees,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
    }
    // Taux d'heure par semestre
    public function volumeCoursParSemestre()
    {
        // Recuperer l'ID du statut "Annulee"
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Recuperer tous les types de seances avec leurs IDs
        $typesSeances = Type_seance::all()->keyBy('id');

        // IDs fixes des deux semestres
        $semestreIds = [
            's1' => 1, // Semestre 1
            's2' => 2  // Semestre 2
        ];

        // Recuperer toutes les classes
        $classes = Classe::all();
        $dataSemestre1 = [];
        $dataSemestre2 = [];

        foreach ($classes as $classe) {
            // Recuperer les annees_classes liees a cette classe
            $anneeClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

            // Types de seances qu'on veut mesurer
            $types = ['Présentiel', 'E-learning', 'Workshop'];
            $heures1 = [];
            $heures2 = [];

            foreach ($typesSeances as $typeId => $type) {
                // Ignorer les types non attendus
                if (!in_array($type->nom, $types)) continue;

                // Calculer le volume en minutes pour le semestre 1
                $minutesSem1 = Seance::whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('type_seance_id', $typeId)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->where('semestre_id', $semestreIds['s1'])
                    ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as minutes'))
                    ->value('minutes');

                // Calculer le volume en minutes pour le semestre 2
                $minutesSem2 = Seance::whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('type_seance_id', $typeId)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->where('semestre_id', $semestreIds['s2'])
                    ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as minutes'))
                    ->value('minutes');

                // Convertir les minutes en heures (arrondi 1 chiffre)
                $heures1[$type->nom] = round(($minutesSem1 ?? 0) / 60, 1);
                $heures2[$type->nom] = round(($minutesSem2 ?? 0) / 60, 1);
            }

            // Ajouter les donnees pour le semestre 1
            $dataSemestre1[] = [
                'classe' => $classe->nom,
                'Présentiel' => $heures1['Présentiel'] ?? 0,
                'E-learning' => $heures1['E-learning'] ?? 0,
                'Workshop' => $heures1['Workshop'] ?? 0,
            ];

            // Ajouter les donnees pour le semestre 2
            $dataSemestre2[] = [
                'classe' => $classe->nom,
                'Présentiel' => $heures2['Présentiel'] ?? 0,
                'E-learning' => $heures2['E-learning'] ?? 0,
                'Workshop' => $heures2['Workshop'] ?? 0,
            ];
        }

        // Afficher la vue avec les donnees des deux semestres
        return view('coordinateur.presences.volume', [
            'dataSemestre1' => $dataSemestre1,
            'dataSemestre2' => $dataSemestre2,
        ]);
    }
    // Volume de cours cumuler par classe
    public function volumeCoursCumule()
    {
        // Recuperer l'ID du statut "Annulee"
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Identifiants des semestres 1 et 2
        $semestres = [
            's1' => 1, // Semestre 1
            's2' => 2  // Semestre 2
        ];

        // Recuperer toutes les classes
        $classes = Classe::all();
        $donnees = [];

        // Parcourir chaque classe pour calculer les heures de cours
        foreach ($classes as $classe) {
            // Recuperer les annees_classes liees a cette classe
            $anneeClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

            $heures = [];

            foreach ($semestres as $cle => $semestreId) {
                // Calculer le volume total en minutes pour le semestre
                $minutes = Seance::whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('semestre_id', $semestreId)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as minutes'))
                    ->value('minutes') ?? 0;

                // Convertir en heures
                $heures[$cle] = round($minutes / 60);
            }

            // Ajouter les donnees dans le tableau final
            $donnees[] = (object)[
                'classe' => $classe->nom,
                's1' => $heures['s1'] ?? 0,
                's2' => $heures['s2'] ?? 0,
                'total' => ($heures['s1'] ?? 0) + ($heures['s2'] ?? 0)
            ];
        }

        // Trier les classes par total d'heures en ordre decroissant
        $donnees = collect($donnees)->sortByDesc('total')->values();

        // Afficher la vue avec les donnees cumulees
        return view('coordinateur.presences.volume-cumule', compact('donnees'));
    }

    // Taux de presence global par classe
    public function tauxPresenceGlobalParClasse(Request $request)
    {
        // Recuperer la periode selectionnee (par defaut : annee)
        $periode = $request->input('periode', 'annee');

        $dateFin = now();
        $dateDebut = null;

        // Definir les dates selon la periode choisie
        switch ($periode) {
            case 'semaine':
                $dateDebut = now()->startOfWeek();
                break;
            case 'semestre':
                $dateDebut = now()->subMonths(6);
                break;
            default:
                $dateDebut = now()->startOfYear();
        }

        // Recuperer toutes les classes avec leurs annees_classes
        $classes = Classe::with('anneesClasses')->get();
        $donnees = [];

        foreach ($classes as $classe) {
            // Recuperer les IDs des annees_classes pour la classe
            $anneeClasseIds = $classe->anneesClasses->pluck('id');

            // Recuperer les IDs des etudiants inscrits dans ces annees_classes
            $etudiantIds = DB::table('annee_classe_etudiant')
                ->whereIn('annee_classe_id', $anneeClasseIds)
                ->pluck('etudiant_id');

            // Si aucun etudiant n'est trouve, on saute cette classe
            if ($etudiantIds->isEmpty()) {
                continue;
            }

            // Compter les presences et les absences pour ces etudiants pendant la periode
            $presences = Presence::whereIn('etudiant_id', $etudiantIds)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count();

            $absences = Absence::whereIn('etudiant_id', $etudiantIds)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count();

            $total = $presences + $absences;

            // Calcul du taux de presence global pour la classe
            $taux = $total > 0 ? round(($presences / $total) * 100, 1) : 0;

            // Ajouter les donnees dans la liste
            $donnees[] = (object)[
                'classe' => $classe->nom,
                'presences' => $presences,
                'absences' => $absences,
                'taux' => $taux,
            ];
        }

        // Trier les classes par taux de presence decroissant
        return view('coordinateur.presences.taux-global', [
            'donnees' => collect($donnees)->sortByDesc('taux')->values(),
            'periode' => $periode,
        ]);
    }
}

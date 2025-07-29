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
    public function assiduiteEtudiantParMatiere(Request $request)
    {
        $etudiants = Etudiant::with('user')->get();
        $etudiantId = $request->input('etudiant_id');
        $notes = [];

        if ($etudiantId) {
            $statutsPresence = Statut_presence::whereIn('libelle', ['Présent', 'En retard'])->pluck('id')->toArray();
            $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

            // 1. Récupérer les annee_classe_id de l'étudiant
            $anneeClasseIds = DB::table('annee_classe_etudiant')
                ->where('etudiant_id', $etudiantId)
                ->pluck('annee_classe_id')
                ->toArray();

            // 2. Trouver les matières concernées par des séances pour ces classes
            $matiereIds = Seance::whereIn('annee_classe_id', $anneeClasseIds)
                ->where('statut_seance_id', '!=', $statutAnnuleeId)
                ->pluck('matiere_id')
                ->unique()
                ->toArray();

            // 3. Charger uniquement ces matières
            $matieres = Matiere::whereIn('id', $matiereIds)->get();

            foreach ($matieres as $matiere) {
                // Total des séances pour cette matière dans ses classes
                $totalSeances = Seance::where('matiere_id', $matiere->id)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->whereIn('annee_classe_id', $anneeClasseIds)
                    ->count();

                // Présences marquées
                $nombrePresences = Presence::where('etudiant_id', $etudiantId)
                    ->whereIn('statuts_presence_id', $statutsPresence)
                    ->whereHas('seance', function ($query) use ($matiere, $statutAnnuleeId, $anneeClasseIds) {
                        $query->where('matiere_id', $matiere->id)
                            ->where('statut_seance_id', '!=', $statutAnnuleeId)
                            ->whereIn('annee_classe_id', $anneeClasseIds);
                    })
                    ->count();

                $note = $totalSeances > 0 ? round(($nombrePresences / $totalSeances) * 20, 2) : 0;

                $notes[] = [
                    'matiere' => $matiere->nom,
                    'total' => $totalSeances,
                    'presences' => $nombrePresences,
                    'note' => $note
                ];
            }
        }

        return view('coordinateur.assiduite', [
            'etudiants' => $etudiants,
            'notes' => $notes,
            'selectedEtudiantId' => $etudiantId,
        ]);
    }

    public function tauxPresenceEtudiant(Request $request)
    {
        $etudiants = Etudiant::with('user')->get();
        $statuts = Statut_presence::whereIn('libelle', ['Présent', 'En retard'])->pluck('id');

        $etudiantId = $request->etudiant_id;
        $periode = $request->periode;
        $dateDebut = null;
        $dateFin = null;

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

        if ($etudiantId && $dateDebut && $dateFin) {
            $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

            $totalSeances = Seance::whereBetween('date_debut', [$dateDebut, $dateFin])
                ->where('statut_seance_id', '!=', $statutAnnuleeId)
                ->whereHas('anneeClasse.etudiants', function ($query) use ($etudiantId) {
                    $query->where('etudiants.id', $etudiantId);
                })
                ->count();

            $totalPresences = Presence::where('etudiant_id', $etudiantId)
                ->whereIn('statuts_presence_id', $statuts)
                ->whereHas('seance', function ($query) use ($dateDebut, $dateFin, $statutAnnuleeId) {
                    $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                        ->where('statut_seance_id', '!=', $statutAnnuleeId);
                })
                ->count();

            $taux = $totalSeances > 0 ? round(($totalPresences / $totalSeances) * 100, 2) : null;
        }

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

    public function selectionClasse()
    {
        $classes = Classe::all();

        return view('coordinateur.presences.selection', [
            'classes' => $classes,
            'donnees' => collect(),
            'classe' => null
        ]);
    }

    public function graphiquePresence(Request $request, Classe $classe)
    {
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Période de filtrage
        $dateDebut = $request->input('date_debut') ? Carbon::parse($request->input('date_debut'))->startOfDay() : null;
        $dateFin = $request->input('date_fin') ? Carbon::parse($request->input('date_fin'))->endOfDay() : null;

        // Année_classe de la classe sélectionnée
        $anneesClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

        // Étudiants liés à ces années_classe
        $etudiants = DB::table('annee_classe_etudiant')
            ->whereIn('annee_classe_id', $anneesClasseIds)
            ->join('etudiants', 'etudiants.id', '=', 'annee_classe_etudiant.etudiant_id')
            ->join('users', 'users.id', '=', 'etudiants.user_id')
            ->select('etudiants.id as etudiant_id', DB::raw("CONCAT(users.prenom, ' ', users.nom) as nom_complet"))
            ->get();

        // Séances valides de cette classe (non annulées)
        $seancesQuery = Seance::whereIn('annee_classe_id', $anneesClasseIds)
            ->where('statut_seance_id', '!=', $statutAnnuleeId);

        if ($dateDebut && $dateFin) {
            $seancesQuery->whereBetween('date_debut', [$dateDebut, $dateFin]);
        }

        $seances = $seancesQuery->get();
        $seanceIds = $seances->pluck('id');

        // Calcul du taux pour chaque étudiant
        $donnees = $etudiants->map(function ($etudiant) use ($seanceIds) {
            $nbPresences = Presence::where('etudiant_id', $etudiant->etudiant_id)
                ->whereIn('seance_id', $seanceIds)
                ->count();

            $nbAbsences = Absence::where('etudiant_id', $etudiant->etudiant_id)
                ->whereIn('seance_id', $seanceIds)
                ->count();

            $total = $nbPresences + $nbAbsences;

            $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : 0;

            return (object)[
                'nom' => $etudiant->nom_complet,
                'taux' => $taux
            ];
        })->sortByDesc('taux')->values();

        return view('coordinateur.presences.graphique', [
            'classe' => $classe,
            'donnees' => $donnees,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
    }

    public function tauxPresenceParClasse(Request $request)
    {
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Dates filtrées
        $dateDebut = $request->input('date_debut') ? Carbon::parse($request->input('date_debut'))->startOfDay() : null;
        $dateFin = $request->input('date_fin') ? Carbon::parse($request->input('date_fin'))->endOfDay() : null;

        $classes = Classe::all();
        $donnees = [];

        foreach ($classes as $classe) {
            $anneesClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

            $seancesQuery = Seance::whereIn('annee_classe_id', $anneesClasseIds)
                ->where('statut_seance_id', '!=', $statutAnnuleeId);

            if ($dateDebut && $dateFin) {
                $seancesQuery->whereBetween('date_debut', [$dateDebut, $dateFin]);
            }

            $seanceIds = $seancesQuery->pluck('id');

            // Présences & absences de tous les étudiants de cette classe
            $nbPresences = Presence::whereIn('seance_id', $seanceIds)->count();
            $nbAbsences = Absence::whereIn('seance_id', $seanceIds)->count();
            $total = $nbPresences + $nbAbsences;

            $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : 0;

            $donnees[] = (object)[
                'classe' => $classe->nom,
                'taux' => $taux
            ];
        }

        // Tri décroissant
        usort($donnees, fn($a, $b) => $b->taux <=> $a->taux);

        return view('coordinateur.presences.parClasse', [
            'donnees' => $donnees,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
    }
    public function volumeCoursParSemestre()
    {
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');
        $typesSeances = Type_seance::all()->keyBy('id');

        $semestreIds = [
            's1' => 1, // ID du Semestre 1
            's2' => 2, // ID du Semestre 2
        ];

        $classes = Classe::all();
        $dataSemestre1 = [];
        $dataSemestre2 = [];

        foreach ($classes as $classe) {
            $anneeClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

            $types = ['Présentiel', 'E-learning', 'Workshop'];
            $heures1 = [];
            $heures2 = [];

            foreach ($typesSeances as $typeId => $type) {
                if (!in_array($type->nom, $types)) continue;

                // SEMESTRE 1 (semestre_id = 1)
                $minutesSem1 = \App\Models\Seance::whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('type_seance_id', $typeId)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->where('semestre_id', $semestreIds['s1'])
                    ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as minutes'))
                    ->value('minutes');

                // SEMESTRE 2 (semestre_id = 2)
                $minutesSem2 = \App\Models\Seance::whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('type_seance_id', $typeId)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->where('semestre_id', $semestreIds['s2'])
                    ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as minutes'))
                    ->value('minutes');

                $heures1[$type->nom] = round(($minutesSem1 ?? 0) / 60, 1);
                $heures2[$type->nom] = round(($minutesSem2 ?? 0) / 60, 1);
            }

            $dataSemestre1[] = [
                'classe' => $classe->nom,
                'Présentiel' => $heures1['Présentiel'] ?? 0,
                'E-learning' => $heures1['E-learning'] ?? 0,
                'Workshop' => $heures1['Workshop'] ?? 0,
            ];

            $dataSemestre2[] = [
                'classe' => $classe->nom,
                'Présentiel' => $heures2['Présentiel'] ?? 0,
                'E-learning' => $heures2['E-learning'] ?? 0,
                'Workshop' => $heures2['Workshop'] ?? 0,
            ];
        }

        return view('coordinateur.presences.volume', [
            'dataSemestre1' => $dataSemestre1,
            'dataSemestre2' => $dataSemestre2,
        ]);
    }

    public function volumeCoursCumule()
    {
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        $semestres = [
            's1' => 1, // ID du Semestre 1
            's2' => 2  // ID du Semestre 2
        ];

        $classes = Classe::all();
        $donnees = [];

        foreach ($classes as $classe) {
            $anneeClasseIds = AnneeClasse::where('classe_id', $classe->id)->pluck('id');

            $heures = [];

            foreach ($semestres as $cle => $semestreId) {
                $minutes = Seance::whereIn('annee_classe_id', $anneeClasseIds)
                    ->where('semestre_id', $semestreId)
                    ->where('statut_seance_id', '!=', $statutAnnuleeId)
                    ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as minutes'))
                    ->value('minutes') ?? 0;

                $heures[$cle] = round($minutes / 60); // conversion en heures
            }

            $donnees[] = (object)[
                'classe' => $classe->nom,
                's1' => $heures['s1'] ?? 0,
                's2' => $heures['s2'] ?? 0,
                'total' => ($heures['s1'] ?? 0) + ($heures['s2'] ?? 0)
            ];
        }

        $donnees = collect($donnees)->sortByDesc('total')->values();

        return view('coordinateur.presences.volume-cumule', compact('donnees'));
    }

    public function tauxPresenceGlobalParClasse(Request $request)
    {
        $periode = $request->input('periode', 'annee');

        $dateFin = now();
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

        $classes = Classe::with('anneesClasses')->get();
        $donnees = [];

        foreach ($classes as $classe) {
            $anneeClasseIds = $classe->anneesClasses->pluck('id');

            $etudiantIds = DB::table('annee_classe_etudiant')
                ->whereIn('annee_classe_id', $anneeClasseIds)
                ->pluck('etudiant_id');

            if ($etudiantIds->isEmpty()) {
                continue;
            }

            $presences = Presence::whereIn('etudiant_id', $etudiantIds)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count();

            $absences = Absence::whereIn('etudiant_id', $etudiantIds)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count();

            $total = $presences + $absences;
            $taux = $total > 0 ? round(($presences / $total) * 100, 1) : 0;

            $donnees[] = (object)[
                'classe' => $classe->nom,
                'presences' => $presences,
                'absences' => $absences,
                'taux' => $taux,
            ];
        }

        return view('coordinateur.presences.taux-global', [
            'donnees' => collect($donnees)->sortByDesc('taux')->values(),
            'periode' => $periode,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Seance;
use App\Models\StatutPresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceAbsenceController extends Controller
{
    /**
     * Afficher la page de gestion des présences pour une séance
     */
    public function index($seanceId)
    {
        $seance = Seance::with(['classe.anneesClasses.etudiants', 'matiere', 'professeur'])
                        ->findOrFail($seanceId);

        $etudiants = collect();
        foreach ($seance->classe->anneesClasses as $anneeClasse) {
            $etudiants = $etudiants->merge($anneeClasse->etudiants);
        }
        $etudiants = $etudiants->unique('id');

        $statutsPresence = StatutPresence::all();

        // Récupérer les présences et absences déjà marquées
        $presencesMarquees = Presence::where('seance_id', $seanceId)
                                   ->with('statutPresence')
                                   ->get()
                                   ->keyBy('etudiant_id');

        $absencesMarquees = Absence::where('seance_id', $seanceId)
                                  ->get()
                                  ->keyBy('etudiant_id');

        return view('coordinateur.presences.index', compact(
            'seance',
            'etudiants',
            'statutsPresence',
            'presencesMarquees',
            'absencesMarquees'
        ));
    }

    /**
     * Marquer un étudiant comme présent
     */
    public function marquerPresent(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Supprimer toute absence existante
        Absence::where('etudiant_id', $request->etudiant_id)
               ->where('seance_id', $request->seance_id)
               ->delete();

        // Créer ou mettre à jour la présence avec statut "Présent"
        Presence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'statuts_presence_id' => 1, // "Présent"
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Étudiant marqué présent avec succès');
    }

    /**
     * Marquer un étudiant en retard
     */
    public function marquerRetard(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Supprimer toute absence existante
        Absence::where('etudiant_id', $request->etudiant_id)
               ->where('seance_id', $request->seance_id)
               ->delete();

        // Créer ou mettre à jour la présence avec statut "En retard"
        Presence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'statuts_presence_id' => 3, // "En retard"
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Étudiant marqué en retard avec succès');
    }

    /**
     * Marquer un étudiant comme absent
     */
    public function marquerAbsent(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Supprimer toute présence existante
        Presence::where('etudiant_id', $request->etudiant_id)
                ->where('seance_id', $request->seance_id)
                ->delete();

        // Créer l'absence
        Absence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Étudiant marqué absent avec succès');
    }

    /**
     * Marquer plusieurs étudiants comme présents
     */
    public function marquerPlusieursPresents(Request $request)
    {
        $request->validate([
            'etudiant_ids' => 'required|array',
            'etudiant_ids.*' => 'exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        $nombre = 0;

        foreach ($request->etudiant_ids as $etudiantId) {
            // Supprimer toute absence existante
            Absence::where('etudiant_id', $etudiantId)
                   ->where('seance_id', $request->seance_id)
                   ->delete();

            // Créer ou mettre à jour la présence
            Presence::updateOrCreate(
                [
                    'etudiant_id' => $etudiantId,
                    'seance_id' => $request->seance_id,
                ],
                [
                    'statuts_presence_id' => 1, // "Présent"
                    'created_by' => Auth::id(),
                ]
            );
            $nombre++;
        }

        return redirect()->back()->with('success', "{$nombre} étudiants marqués présents avec succès");
    }

    /**
     * Marquer plusieurs étudiants comme absents
     */
    public function marquerPlusieursAbsents(Request $request)
    {
        $request->validate([
            'etudiant_ids' => 'required|array',
            'etudiant_ids.*' => 'exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        $nombre = 0;

        foreach ($request->etudiant_ids as $etudiantId) {
            // Supprimer toute présence existante
            Presence::where('etudiant_id', $etudiantId)
                    ->where('seance_id', $request->seance_id)
                    ->delete();

            // Créer l'absence
            Absence::updateOrCreate(
                [
                    'etudiant_id' => $etudiantId,
                    'seance_id' => $request->seance_id,
                ],
                [
                    'created_by' => Auth::id(),
                ]
            );
            $nombre++;
        }

        return redirect()->back()->with('success', "{$nombre} étudiants marqués absents avec succès");
    }

    /**
     * Afficher les statistiques de présence
     */
    public function statistiques()
    {
        // Statistiques générales
        $totalPresences = Presence::count();
        $totalAbsences = Absence::count();
        $presentsToday = Presence::whereHas('seance', function($query) {
            $query->whereDate('date_debut', today());
        })->count();

        $statistiquesParClasse = Seance::with(['classe', 'presences.statutPresence', 'absences'])
                                      ->get()
                                      ->groupBy('classe_id')
                                      ->map(function($seances, $classeId) {
                                          $classe = $seances->first()->classe;
                                          $totalPresences = $seances->sum(function($seance) {
                                              return $seance->presences->count();
                                          });
                                          $totalAbsences = $seances->sum(function($seance) {
                                              return $seance->absences->count();
                                          });

                                          $presents = $seances->sum(function($seance) {
                                              return $seance->presences->filter(function($presence) {
                                                  return $presence->statutPresence &&
                                                         $presence->statutPresence->libelle === 'Présent';
                                              })->count();
                                          });

                                          $retards = $seances->sum(function($seance) {
                                              return $seance->presences->filter(function($presence) {
                                                  return $presence->statutPresence &&
                                                         $presence->statutPresence->libelle === 'En retard';
                                              })->count();
                                          });

                                          $total = $totalPresences + $totalAbsences;

                                          return [
                                              'classe' => $classe,
                                              'total_presences' => $totalPresences,
                                              'total_absences' => $totalAbsences,
                                              'presents' => $presents,
                                              'retards' => $retards,
                                              'taux_presence' => $total > 0 ?
                                                  round(($presents / $total) * 100, 1) : 0
                                          ];
                                      });

        return view('coordinateur.presences.statistiques', compact(
            'totalPresences',
            'totalAbsences',
            'presentsToday',
            'statistiquesParClasse'
        ));
    }
}

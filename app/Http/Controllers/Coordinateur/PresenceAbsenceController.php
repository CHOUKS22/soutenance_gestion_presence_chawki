<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Seance;
use App\Models\Statut_presence;
use App\Models\StatutPresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceAbsenceController extends Controller
{
    // Affiche la page de gestion des presences d'une seance
    public function index($seanceId)
    {
        $seance = Seance::with(['classe.anneesClasses.etudiants', 'matiere', 'professeur'])->findOrFail($seanceId);

        // Recuperer les etudiants de toutes les annees de la classe
        $etudiants = collect();
        foreach ($seance->classe->anneesClasses as $anneeClasse) {
            $etudiants = $etudiants->merge($anneeClasse->etudiants);
        }
        $etudiants = $etudiants->unique('id')->load('user');

        // Statuts disponibles
        $statutsPresence = Statut_presence::all();

        // Recuperer les presences et absences deja enregistrees
        $presences = Presence::where('seance_id', $seanceId)->with('statutPresence')->get()->keyBy('etudiant_id');
        $absences = Absence::where('seance_id', $seanceId)->get()->keyBy('etudiant_id');

        // Ajouter un champ statut_presence a chaque etudiant
        foreach ($etudiants as $etudiant) {
            if (isset($presences[$etudiant->id])) {
                $etudiant->statut_presence = $presences[$etudiant->id]->statutPresence->libelle;
            } elseif (isset($absences[$etudiant->id])) {
                $etudiant->statut_presence = 'Absent';
            } else {
                $etudiant->statut_presence = 'Non defini';
            }
        }

        // Calcul des stats
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non defini')->count(),
        ];

        return view('coordinateur.presences.index', compact(
            'seance',
            'etudiants',
            'statutsPresence',
            'statistiques'
        ));
    }

    // Marquer un etudiant present
    public function marquerPresent(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Supprimer une absence si elle existe
        Absence::where('etudiant_id', $request->etudiant_id)
            ->where('seance_id', $request->seance_id)
            ->delete();

        // Enregistrer ou mettre a jour la presence
        Presence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'statuts_presence_id' => 1, // Present
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Etudiant marque present');
    }

    // Marquer un etudiant en retard
    public function marquerRetard(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        Absence::where('etudiant_id', $request->etudiant_id)
            ->where('seance_id', $request->seance_id)
            ->delete();

        Presence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'statuts_presence_id' => 3, // En retard
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Etudiant marque en retard');
    }

    // Marquer un etudiant absent
    public function marquerAbsent(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Supprimer une presence si elle existe
        Presence::where('etudiant_id', $request->etudiant_id)
            ->where('seance_id', $request->seance_id)
            ->delete();

        // Enregistrer l'absence
        Absence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Etudiant marque absent');
    }

    // Afficher les statistiques globales
    public function statistiques()
    {
        $totalPresences = Presence::count();
        $totalAbsences = Absence::count();

        // Nombre de presences aujourd'hui
        $presentsToday = Presence::whereHas('seance', function ($query) {
            $query->whereDate('date_debut', today());
        })->count();

        // Statistiques par classe
        $statistiquesParClasse = Seance::with(['classe', 'presences.statutPresence', 'absences'])
            ->get()
            ->groupBy('classe_id')
            ->map(function ($seances, $classeId) {
                $classe = $seances->first()->classe;

                $totalPresences = $seances->sum(fn($s) => $s->presences->count());
                $totalAbsences = $seances->sum(fn($s) => $s->absences->count());

                $presents = $seances->sum(fn($s) =>
                    $s->presences->filter(fn($p) => $p->statutPresence && $p->statutPresence->libelle === 'Présent')->count()
                );

                $retards = $seances->sum(fn($s) =>
                    $s->presences->filter(fn($p) => $p->statutPresence && $p->statutPresence->libelle === 'En retard')->count()
                );

                $total = $totalPresences + $totalAbsences;

                return [
                    'classe' => $classe,
                    'total_presences' => $totalPresences,
                    'total_absences' => $totalAbsences,
                    'presents' => $presents,
                    'retards' => $retards,
                    'taux_presence' => $total > 0 ? round(($presents / $total) * 100, 1) : 0,
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

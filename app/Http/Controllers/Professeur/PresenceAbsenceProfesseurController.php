<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Seance;
use App\Models\Statut_presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceAbsenceProfesseurController extends Controller
{
    /**
     * Affiche la liste des etudiants d'une seance
     * avec leurs presences/absences
     */
    public function index($seanceId)
    {
        // Recuperer la seance avec les infos utiles
        $seance = Seance::with(['anneeClasse.classe', 'matiere', 'professeur'])
            ->findOrFail($seanceId);

        // Recuperer tous les etudiants lies a la classe (meme sur plusieurs annees)
        $etudiants = collect();
        foreach ($seance->anneeClasse->classe->anneesClasses as $anneeClasse) {
            $etudiants = $etudiants->merge($anneeClasse->etudiants);
        }

        // Supprimer les doublons et charger les infos des utilisateurs
        $etudiants = $etudiants->unique('id')->load('user');

        // Tous les statuts disponibles (Present, En retard, etc.)
        $statutsPresence = Statut_presence::all();

        // Recuperer les presences et absences deja enregistrees
        $presences = Presence::where('seance_id', $seanceId)
            ->with('statutPresence')
            ->get()
            ->keyBy('etudiant_id');

        $absences = Absence::where('seance_id', $seanceId)
            ->get()
            ->keyBy('etudiant_id');

        // On ajoute une propriete "statut_presence" a chaque etudiant
        foreach ($etudiants as $etudiant) {
            if (isset($presences[$etudiant->id])) {
                $etudiant->statut_presence = $presences[$etudiant->id]->statutPresence->libelle;
            } elseif (isset($absences[$etudiant->id])) {
                $etudiant->statut_presence = 'Absent';
            } else {
                $etudiant->statut_presence = 'Non defini';
            }
        }

        // Calcul de quelques statistiques pour la seance
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non defini')->count(),
        ];

        return view('professeur.seances.index', compact(
            'seance',
            'etudiants',
            'statutsPresence',
            'statistiques'
        ));
    }

    /**
     * Marque un etudiant comme present
     */
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

        // Ajouter ou mettre a jour la presence avec le statut "Present"
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

        return redirect()->back()->with('success', 'Etudiant marque present avec succes');
    }

    /**
     * Marque un etudiant en retard
     */
    public function marquerRetard(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Supprimer une absence si elle existe
        Absence::where('etudiant_id', $request->etudiant_id)
            ->where('seance_id', $request->seance_id)
            ->delete();

        // Ajouter ou mettre a jour la presence avec le statut "En retard"
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

        return redirect()->back()->with('success', 'Etudiant marque en retard avec succes');
    }

    /**
     * Marque un etudiant comme absent
     */
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

        // Ajouter l'absence
        Absence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Etudiant marque absent avec succes');
    }
}

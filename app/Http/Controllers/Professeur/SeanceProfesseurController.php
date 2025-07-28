<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Seance;
use App\Models\Statut_presence;
use App\Models\Statut_seance;
use App\Models\Type_seance;
use Illuminate\Support\Facades\Auth;

class SeanceProfesseurController extends Controller
{
    /**
     * Affiche la liste des séances présentielles du professeur connecté.
     */
     public function index()
    {
        // Récupère l'ID du professeur lié à l'utilisateur connecté
        $professeur = Auth::user()->professeur;

        // Vérifie si l'utilisateur est bien lié à un professeur
        if (!$professeur) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à ces séances');
        }

        // Optionnel : ID du statut "Annulée", à utiliser si filtrage nécessaire
        $statutAnnuleeId = Statut_seance::where('libelle', 'Annulée')->value('id');

        // Récupère uniquement les séances du professeur connecté
        $seances = Seance::with([
                'anneeClasse.classe',
                'matiere',
                'professeur',
                'statutSeance',
                'semestre',
                'typeSeance',
                'presences',
                'absences'
            ])
            ->where('professeur_id', $professeur->id)
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        return view('professeur.seances.index', compact('seances'));
    }
    public function show($id)
    {
        $seance = Seance::with(['anneeClasse.classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance'])->findOrFail($id);
        $etudiants = collect();

        if ($seance->annee_classe_id) {
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($seance) {
                $query->where('annee_classe_id', $seance->annee_classe_id);
            })->with('user')->get();
        }

        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        $etudiants = $etudiants->map(function ($etudiant) use ($presences, $absences) {
            if (isset($presences[$etudiant->id])) {
                $etudiant->statut_presence = $presences[$etudiant->id];
            } elseif (in_array($etudiant->id, $absences)) {
                $etudiant->statut_presence = 'Absent';
            } else {
                $etudiant->statut_presence = 'Non défini';
            }
            return $etudiant;
        });

        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non défini')->count(),
        ];

        $presences = Presence::where('seance_id', $seance->id)->with('statutPresence')->get()->keyBy('etudiant_id');

        return view('professeur.seances.show', compact('seance', 'etudiants', 'statistiques', 'presences'));
    }

    public function presences($id)
    {
        $seance = Seance::with(['anneeClasse.classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance'])->findOrFail($id);
        $etudiants = collect();

        if ($seance->annee_classe_id) {
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($seance) {
                $query->where('annee_classe_id', $seance->annee_classe_id);
            })->with('user')->get();
        }

        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        $etudiants = $etudiants->map(function ($etudiant) use ($presences, $absences) {
            if (isset($presences[$etudiant->id])) {
                $etudiant->statut_presence = $presences[$etudiant->id];
            } elseif (in_array($etudiant->id, $absences)) {
                $etudiant->statut_presence = 'Absent';
            } else {
                $etudiant->statut_presence = 'Non défini';
            }
            return $etudiant;
        });

        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non défini')->count(),
        ];

        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->keyBy('etudiant_id');

        $statutsPresence = Statut_presence::all();

        return view('professeur.seances.presences', compact('seance', 'etudiants', 'statistiques', 'presences', 'statutsPresence'));
    }
}

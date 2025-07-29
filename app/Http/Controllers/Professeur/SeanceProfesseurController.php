<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Seance;
use App\Models\Statut_presence;
use App\Models\Statut_seance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SeanceProfesseurController extends Controller
{
    /**
     * Affiche la liste des seances du professeur connecte
     */
    public function index()
    {
        $professeur = Auth::user()->professeur;

        if (!$professeur) {
            // Si l'utilisateur n'est pas un professeur, on bloque l'acces
            abort(403, 'Vous n etes pas autorise a acceder a ces seances');
        }

        // On recupere toutes les seances liees a ce professeur
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

    /**
     * Affiche les details d une seance et les statuts des etudiants
     */
    public function show($id)
    {
        $seance = Seance::with([
            'anneeClasse.classe',
            'matiere',
            'professeur',
            'statutSeance',
            'semestre',
            'typeSeance'
        ])->findOrFail($id);

        // Recuperer les etudiants de la classe concerne par cette seance
        $etudiants = collect();
        if ($seance->annee_classe_id) {
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($seance) {
                $query->where('annee_classe_id', $seance->annee_classe_id);
            })->with('user')->get();
        }

        // On recupere les presences et absences pour cette seance
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        // Ajouter le champ de statut a chaque etudiant
        $etudiants = $etudiants->map(function ($etudiant) use ($presences, $absences) {
            $etudiant->statut_presence = $presences[$etudiant->id] ?? (in_array($etudiant->id, $absences) ? 'Absent' : 'Non defini');
            return $etudiant;
        });

        // Statistiques simples
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non defini')->count(),
        ];

        // Pour l'affichage detaille dans la vue
        $presences = Presence::where('seance_id', $seance->id)->with('statutPresence')->get()->keyBy('etudiant_id');

        return view('professeur.seances.show', compact('seance', 'etudiants', 'statistiques', 'presences'));
    }

    /**
     * Affiche la page pour marquer les presences (sous conditions)
     */
    public function presences($id)
    {
        $seance = Seance::with([
            'anneeClasse.classe',
            'matiere',
            'professeur',
            'statutSeance',
            'semestre',
            'typeSeance'
        ])->findOrFail($id);

        // Regle 1 : on autorise uniquement les seances en presentiel
        if ($seance->typeSeance->nom !== 'Présentiel') {
            abort(403, 'Cette seance n est pas de type presentiel. Vous ne pouvez pas marquer les presences.');
        }

        // Regle 2 : verifier si la limite de 14 jours est depassee
        $dateDebut = Carbon::parse($seance->date_debut);
        $dateLimite = $dateDebut->copy()->addDays(14);

        if (now()->greaterThan($dateLimite)) {
            abort(403, 'Le delai de 14 jours pour marquer les presences est depasse.');
        }

        // Recuperer les etudiants inscrits dans la classe de la seance
        $etudiants = collect();
        if ($seance->annee_classe_id) {
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($seance) {
                $query->where('annee_classe_id', $seance->annee_classe_id);
            })->with('user')->get();
        }

        // Recuperer les presences et absences associees
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        // Ajouter le statut presence a chaque etudiant
        $etudiants = $etudiants->map(function ($etudiant) use ($presences, $absences) {
            $etudiant->statut_presence = $presences[$etudiant->id] ?? (in_array($etudiant->id, $absences) ? 'Absent' : 'Non defini');
            return $etudiant;
        });

        // Preparer les donnees pour les statistiques
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non defini')->count(),
        ];

        // Recuperer les presences (details pour la vue)
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->keyBy('etudiant_id');

        $statutsPresence = Statut_presence::all();

        return view('professeur.seances.presences', compact(
            'seance',
            'etudiants',
            'statistiques',
            'presences',
            'statutsPresence'
        ));
    }
}

<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Presence;
use App\Models\Seance;
use App\Models\Statut_presence;
use App\Models\StatutPresence;
use Illuminate\Http\Request;

class StatistiquesPresenceController extends Controller
{
    public function assiduiteEtudiantParMatiere(Request $request)
    {
        $etudiants = Etudiant::with('user')->get();
        $selectedEtudiantId = $request->input('etudiant_id');

        $matieres = Matiere::all();
        $resultats = [];

        if ($selectedEtudiantId) {
            // Récupération des IDs des statuts considérés comme "présences"
            $statutsPresence = Statut_presence::whereIn('libelle', ['Présent', 'En retard'])->pluck('id');

            foreach ($matieres as $matiere) {
                // Total de séances de la matière pour les classes de l'étudiant
                $totalSeances = Seance::where('matiere_id', $matiere->id)
                    ->whereHas('classe.anneesClasses.etudiants', function ($q) use ($selectedEtudiantId) {
                        $q->where('etudiants.id', $selectedEtudiantId);
                    })
                    ->count();

                // Nombre de présences
                $presences = Presence::where('etudiant_id', $selectedEtudiantId)
                    ->whereHas('seance', function ($q) use ($matiere) {
                        $q->where('matiere_id', $matiere->id);
                    })
                    ->whereIn('statuts_presence_id', $statutsPresence)
                    ->count();

                // Calcul de la note sur 20
                $note = $totalSeances > 0 ? round(($presences / $totalSeances) * 20) : 0;

                $resultats[] = [
                    'matiere' => $matiere->nom,
                    'total_seances' => $totalSeances,
                    'presences' => $presences,
                    'note' => $note
                ];
            }
        }

        return view('coordinateur.assiduite', compact('etudiants', 'resultats', 'selectedEtudiantId'));
    }
}

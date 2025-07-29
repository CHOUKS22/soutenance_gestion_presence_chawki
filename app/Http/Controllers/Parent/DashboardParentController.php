<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;
use App\Models\Seance;
use App\Models\Presence;
use App\Models\Absence;
use Carbon\Carbon;

class DashboardParentController extends Controller
{
    public function index(Request $request)
    {
        // Recuperer le parent connecte
        $parent = Auth::user()->parent;
        if (!$parent) abort(403, 'Acces refuse.');

        // Recuperer les etudiants lies au parent
        $etudiants = $parent->etudiants;
        $etudiantSelectionne = null;

        // Initialiser les collections,fournit des méthodes pratiques(map(),filter()..etc)
        $seances = collect();
        $presences = collect();
        $retards = collect();
        $absencesJustifiees = collect();
        $absencesNonJustifiees = collect();

        $taux = 0;
        $total = 0;

        // Recuperer la periode selectionnee (par defaut : semaine)
        $periode = $request->get('periode', 'semaine');

        // Recuperer la semaine selectionnee (ou aujourd'hui par defaut)
        $dateSemaine = $request->get('date_semaine', now()->toDateString());

        // Si un etudiant est selectionne
        if ($request->filled('etudiant_id')) {
            $etudiantSelectionne = $etudiants->where('id', $request->etudiant_id)->first();

            if ($etudiantSelectionne) {
                $etudiantId = $etudiantSelectionne->id;

                // Definir la date de debut selon la periode selectionnee
                switch ($periode) {
                    case 'mois':
                        $dateDebut = now()->startOfMonth();
                        break;
                    case 'annee':
                        $dateDebut = now()->startOfYear();
                        break;
                    default:
                        $dateDebut = now()->startOfWeek();
                        break;
                }

                $dateFin = now()->endOfDay();

                // Recuperer la semaine a afficher dans l'emploi du temps
                $debutSemaine = Carbon::parse($dateSemaine)->startOfWeek();
                $finSemaine = Carbon::parse($dateSemaine)->endOfWeek();

                // Recuperer les seances pour l'emploi du temps
                $seances = Seance::whereHas('anneeClasse.etudiants', function ($query) use ($etudiantId) {
                    $query->where('etudiants.id', $etudiantId);
                })
                    ->whereBetween('date_debut', [$debutSemaine, $finSemaine])
                    ->with(['matiere', 'anneeClasse.classe', 'typeSeance'])
                    ->orderBy('date_debut', 'asc')
                    ->get();

                // Recuperer les presences (present) durant la periode
                $presences = Presence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereHas('statutPresence', fn($q) => $q->where('libelle', 'Présent'))
                    ->get();

                // Recuperer les retards durant la periode
                $retards = Presence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereHas('statutPresence', fn($q) => $q->where('libelle', 'En retard'))
                    ->get();

                // Recuperer les absences non justifiees
                $absencesNonJustifiees = Absence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereDoesntHave('justifications')
                    ->get();

                // Recuperer les absences justifiees
                $absencesJustifiees = Absence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereHas('justifications')
                    ->get();

                // Calcul du taux de presence global
                $nbPresences = $presences->count() + $retards->count();
                $nbAbsences = $absencesJustifiees->count() + $absencesNonJustifiees->count();
                $total = $nbPresences + $nbAbsences;

                $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : 0;
            }
        }

        // Retourner la vue avec toutes les donnees du dashboard parent
        return view('parent.dashboard', compact(
            'etudiants',
            'etudiantSelectionne',
            'periode',
            'dateSemaine',
            'seances',
            'presences',
            'retards',
            'absencesJustifiees',
            'absencesNonJustifiees',
            'taux',
            'total'
        ));
    }
}

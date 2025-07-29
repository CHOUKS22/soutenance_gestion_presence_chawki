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
        $parent = Auth::user()->parent;
        if (!$parent) abort(403, 'Accès refusé.');

        $etudiants = $parent->etudiants;
        $etudiantSelectionne = null;

        $seances = collect();
        $presences = collect();
        $retards = collect();
        $absencesJustifiees = collect();
        $absencesNonJustifiees = collect();
        $taux = 0;
        $total = 0;

        $periode = $request->get('periode', 'semaine');
        $dateSemaine = $request->get('date_semaine', now()->toDateString());

        if ($request->filled('etudiant_id')) {
            $etudiantSelectionne = $etudiants->where('id', $request->etudiant_id)->first();

            if ($etudiantSelectionne) {
                $etudiantId = $etudiantSelectionne->id;

                // Période pour taux de présence
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

                // Récupération de l’emploi du temps (par semaine sélectionnée)
                $debutSemaine = Carbon::parse($dateSemaine)->startOfWeek();
                $finSemaine = Carbon::parse($dateSemaine)->endOfWeek();

                $seances = Seance::whereHas('anneeClasse.etudiants', function ($query) use ($etudiantId) {
                    $query->where('etudiants.id', $etudiantId);
                })
                    ->whereBetween('date_debut', [$debutSemaine, $finSemaine])
                    ->with(['matiere', 'anneeClasse.classe', 'typeSeance'])
                    ->orderBy('date_debut', 'asc')
                    ->get();

                // Présences et retards (période)
                $presences = Presence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereHas('statutPresence', fn($q) => $q->where('libelle', 'Présent'))
                    ->get();

                $retards = Presence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereHas('statutPresence', fn($q) => $q->where('libelle', 'En retard'))
                    ->get();

                $absencesNonJustifiees = Absence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereDoesntHave('justifications')
                    ->get();

                $absencesJustifiees = Absence::with('seance.matiere', 'seance.typeSeance')
                    ->where('etudiant_id', $etudiantId)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->whereHas('justifications')
                    ->get();

                $nbPresences = $presences->count() + $retards->count();
                $nbAbsences = $absencesJustifiees->count() + $absencesNonJustifiees->count();
                $total = $nbPresences + $nbAbsences;
                $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : 0;
            }
        }

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

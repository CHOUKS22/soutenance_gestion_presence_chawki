<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AnneeClasse;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Presence;
use App\Models\Professeur;
use App\Models\Statut_seance;
use App\Models\Semestre;
use App\Models\Statut_presence;
use App\Models\Type_seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SeanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seances = Seance::with(['classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance', 'presences','absences'])
            ->orderBy('date_debut', 'desc')
            ->paginate(10);
        return view('coordinateur.seances.seances', compact('seances'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $classes = Classe::with('anneesClasses.anneeAcademique')->get();
        $matieres = Matiere::all();
        $professeurs = Professeur::all();
        $statuts = Statut_seance::all();
        $semestres = Semestre::all();
        $types = Type_seance::all();

        return view('coordinateur.seances.seance-create', compact('classes', 'matieres', 'professeurs', 'statuts', 'semestres', 'types'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'classe_id' => 'required|exists:classes,id',
                'matiere_id' => 'required|exists:matieres,id',
                'professeur_id' => 'required|exists:professeurs,id',
                'statut_seance_id' => 'required|exists:statuts_seances,id',
                'semestre_id' => 'required|exists:semestres,id',
                'type_seance_id' => 'required|exists:types_seances,id',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after:date_debut',
            ]);

            $seance = Seance::create($request->only([
                'classe_id',
                'matiere_id',
                'professeur_id',
                'statut_seance_id',
                'semestre_id',
                'type_seance_id',
                'date_debut',
                'date_fin'
            ]));

            return redirect()->route('seances.index')->with('success', 'Séance créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création de la séance: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($gestion_seance)
    {
        // Essayer de résoudre manuellement le modèle
        $seance = Seance::find($gestion_seance);

        $seance->load(['classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance']);

        // Récupérer les étudiants inscrits dans cette classe
        // Récupérer l'AnneeClasse pour cette classe (la plus récente)
        $anneeClasse = AnneeClasse::where('classe_id', $seance->classe_id)
            ->with('anneeAcademique')
            ->orderBy('created_at', 'desc')
            ->first();

        $etudiants = collect();

        if ($anneeClasse) {
            // Récupérer les étudiants via la table pivot
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($anneeClasse) {
                $query->where('annee_classe_id', $anneeClasse->id);
            })
                ->with(['user'])
                ->orderBy('id')
                ->get();
        }

        // Récupérer les présences et absences pour cette séance
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        // Enrichir les étudiants avec leur statut de présence
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

        // Calculer les statistiques de présence
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non défini')->count(),
        ];
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence') // pour avoir le libellé
            ->get()
            ->keyBy('etudiant_id');
        return view('coordinateur.seances.seance-show', compact('seance', 'etudiants', 'statistiques', 'presences'));
    }
    public function presences($gestion_seance)
    {
        // Essayer de résoudre manuellement le modèle
        $seance = Seance::find($gestion_seance);

        $seance->load(['classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance']);

        // Récupérer les étudiants inscrits dans cette classe
        // Récupérer l'AnneeClasse pour cette classe (la plus récente)
        $anneeClasse = AnneeClasse::where('classe_id', $seance->classe_id)
            ->with('anneeAcademique')
            ->orderBy('created_at', 'desc')
            ->first();

        $etudiants = collect();

        if ($anneeClasse) {
            // Récupérer les étudiants via la table pivot
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($anneeClasse) {
                $query->where('annee_classe_id', $anneeClasse->id);
            })
                ->with(['user'])
                ->orderBy('id')
                ->get();
        }

        // Récupérer les présences et absences pour cette séance
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        // Enrichir les étudiants avec leur statut de présence
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

        // Calculer les statistiques de présence
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Présent')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non défini')->count(),
        ];
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence') // pour avoir le libellé
            ->get()
            ->keyBy('etudiant_id');
            $statutsPresence = Statut_presence::all();
        return view('coordinateur.seances.presences', compact('seance', 'etudiants', 'statistiques', 'presences', 'statutsPresence'));
    }
    public function edit(Seance $seance)
    {
        $classes = Classe::all();
        $matieres = Matiere::all();
        $professeurs = Professeur::all();
        $statuts = Statut_seance::all();
        $semestres = Semestre::all();
        $types = Type_seance::all();

        return view('coordinateur.seances.seance-edit', compact('seance', 'classes', 'matieres', 'professeurs', 'statuts', 'semestres', 'types'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seance $seance)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'statut_seance_id' => 'required|exists:statuts_seances,id',
            'semestre_id' => 'required|exists:semestres,id',
            'type_seance_id' => 'required|exists:types_seances,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $seance->update($request->all());

        return redirect()->route('seances.index')->with('success', 'Séance mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seance $seance)
    {
        $seance->delete();
        return redirect()->route('seances.index')->with('success', 'Séance supprimée avec succès.');
    }
}

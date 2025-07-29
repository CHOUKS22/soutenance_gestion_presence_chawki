<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AnneeClasse;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Presence;
use App\Models\Professeur;
use App\Models\Seance;
use App\Models\Semestre;
use App\Models\Statut_presence;
use App\Models\Statut_seance;
use App\Models\Type_seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeanceController extends Controller
{
    public function index()
    {
        $coordinateur = Auth::user()->coordinateur;

        // Verifie si l'utilisateur est un coordinateur
        if (!$coordinateur) {
            abort(403, 'Acces non autorise.');
        }

        // Recuperer les IDs des annees/classes du coordinateur
        $anneesClasseIds = $coordinateur->anneesClasses()->pluck('id');

        // Recuperer les seances liees aux annees/classes
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
            ->whereIn('annee_classe_id', $anneesClasseIds)
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        return view('coordinateur.seances.seances', compact('seances'));
    }

    public function create()
    {
        $coordinateur = Auth::user()->coordinateur;

        // Verifie si l'utilisateur est un coordinateur
        if (!$coordinateur) {
            abort(403, 'Acces non autorise.');
        }

        // Recuperer les annees/classes liees au coordinateur
        $annees_classes = $coordinateur->anneesClasses()
            ->with('classe', 'anneeAcademique')
            ->latest()
            ->get();

        // Recuperer les donnees pour le formulaire
        $matieres = Matiere::all();
        $professeurs = Professeur::all();
        $statuts = Statut_seance::all();
        $semestres = Semestre::all();
        $types = Type_seance::all();

        return view('coordinateur.seances.seance-create', compact(
            'annees_classes',
            'matieres',
            'professeurs',
            'statuts',
            'semestres',
            'types'
        ));
    }

    public function store(Request $request)
    {
        // Valider les donnees du formulaire
        $request->validate([
            'annee_classe_id' => 'required|exists:annees_classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'statut_seance_id' => 'required|exists:statuts_seances,id',
            'semestre_id' => 'required|exists:semestres,id',
            'type_seance_id' => 'required|exists:types_seances,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_reportee' => 'nullable|date',
            'heure_debut_report' => 'nullable|date_format:H:i',
            'heure_fin_report' => 'nullable|date_format:H:i|after_or_equal:heure_debut_report',
            'commentaire_report' => 'nullable|string|max:1000',
        ]);

        // Verifier s'il y a un conflit de planning pour la classe
        $classeConflit = Seance::where('annee_classe_id', $request->annee_classe_id)
            ->where('date_debut', '<', $request->date_fin)
            ->where('date_fin', '>', $request->date_debut)
            ->exists();

        if ($classeConflit) {
            return back()->withErrors(['date_debut' => 'Cette classe a deja une seance a cette date.'])->withInput();
        }

        // Verifier s'il y a un conflit de planning pour le professeur
        $professeurConflit = Seance::where('professeur_id', $request->professeur_id)
            ->where('date_debut', '<', $request->date_fin)
            ->where('date_fin', '>', $request->date_debut)
            ->exists();

        if ($professeurConflit) {
            return back()->withErrors(['professeur_id' => 'Ce professeur a deja une seance a ce moment.'])->withInput();
        }

        // Creer la seance
        Seance::create($request->only([
            'annee_classe_id',
            'matiere_id',
            'professeur_id',
            'statut_seance_id',
            'semestre_id',
            'type_seance_id',
            'date_debut',
            'date_fin',
            'date_reportee',
            'heure_debut_report',
            'heure_fin_report',
            'commentaire_report',
        ]));

        return redirect()->route('seances.index')->with('success', 'Seance creee avec succes.');
    }

    public function show($id)
    {
        // Recuperer la seance avec ses relations
        $seance = Seance::with(['anneeClasse.classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance'])->findOrFail($id);

        $etudiants = collect();

        // Recuperer les etudiants de la classe si elle existe
        if ($seance->annee_classe_id) {
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($seance) {
                $query->where('annee_classe_id', $seance->annee_classe_id);
            })->with('user')->get();
        }

        // Recuperer les presences et absences
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        // Marquer le statut de presence de chaque etudiant
        $etudiants = $etudiants->map(function ($etudiant) use ($presences, $absences) {
            if (isset($presences[$etudiant->id])) {
                $etudiant->statut_presence = $presences[$etudiant->id];
            } elseif (in_array($etudiant->id, $absences)) {
                $etudiant->statut_presence = 'Absent';
            } else {
                $etudiant->statut_presence = 'Non defini';
            }
            return $etudiant;
        });

        // Statistiques sur la seance
        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Present')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non defini')->count(),
        ];

        $presences = Presence::where('seance_id', $seance->id)->with('statutPresence')->get()->keyBy('etudiant_id');

        return view('coordinateur.seances.seance-show', compact('seance', 'etudiants', 'statistiques', 'presences'));
    }

    public function presences($id)
    {
        // Recuperer la seance avec ses relations
        $seance = Seance::with(['anneeClasse.classe', 'matiere', 'professeur', 'statutSeance', 'semestre', 'typeSeance'])->findOrFail($id);

        $etudiants = collect();

        // Recuperer les etudiants de la classe si elle existe
        if ($seance->annee_classe_id) {
            $etudiants = Etudiant::whereHas('anneeClasseEtudiants', function ($query) use ($seance) {
                $query->where('annee_classe_id', $seance->annee_classe_id);
            })->with('user')->get();
        }

        // Recuperer les presences et absences
        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->pluck('statutPresence.libelle', 'etudiant_id')
            ->toArray();

        $absences = Absence::where('seance_id', $seance->id)
            ->pluck('etudiant_id')
            ->toArray();

        // Marquer le statut de chaque etudiant
        $etudiants = $etudiants->map(function ($etudiant) use ($presences, $absences) {
            if (isset($presences[$etudiant->id])) {
                $etudiant->statut_presence = $presences[$etudiant->id];
            } elseif (in_array($etudiant->id, $absences)) {
                $etudiant->statut_presence = 'Absent';
            } else {
                $etudiant->statut_presence = 'Non defini';
            }
            return $etudiant;
        });

        $statistiques = [
            'total' => $etudiants->count(),
            'presents' => $etudiants->where('statut_presence', 'Present')->count(),
            'retards' => $etudiants->where('statut_presence', 'En retard')->count(),
            'absents' => $etudiants->where('statut_presence', 'Absent')->count(),
            'non_definis' => $etudiants->where('statut_presence', 'Non defini')->count(),
        ];

        $presences = Presence::where('seance_id', $seance->id)
            ->with('statutPresence')
            ->get()
            ->keyBy('etudiant_id');

        $statutsPresence = Statut_presence::all();

        return view('coordinateur.seances.presences', compact('seance', 'etudiants', 'statistiques', 'presences', 'statutsPresence'));
    }

    public function edit(Seance $seance)
    {
        // Recuperer les donnees pour le formulaire
        $annees_classes = AnneeClasse::with('classe', 'anneeAcademique')->latest()->take(15)->get();
        $matieres = Matiere::all();
        $professeurs = Professeur::all();
        $statuts = Statut_seance::all();
        $semestres = Semestre::all();
        $types = Type_seance::all();

        return view('coordinateur.seances.seance-edit', compact('seance', 'annees_classes', 'matieres', 'professeurs', 'statuts', 'semestres', 'types'));
    }

    public function update(Request $request, Seance $seance)
    {
        // Valider les donnees du formulaire
        $request->validate([
            'annee_classe_id' => 'required|exists:annees_classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'statut_seance_id' => 'required|exists:statuts_seances,id',
            'semestre_id' => 'required|exists:semestres,id',
            'type_seance_id' => 'required|exists:types_seances,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_reportee' => 'nullable|date',
            'heure_debut_report' => 'nullable|date_format:H:i',
            'heure_fin_report' => 'nullable|date_format:H:i|after_or_equal:heure_debut_report',
            'commentaire_report' => 'nullable|string|max:1000',
        ]);

        // Verifier les conflits de classe (en ignorant la seance actuelle)
        $classeConflit = Seance::where('annee_classe_id', $request->annee_classe_id)
            ->where('id', '!=', $seance->id)
            ->where('date_debut', '<', $request->date_fin)
            ->where('date_fin', '>', $request->date_debut)
            ->exists();

        if ($classeConflit) {
            return back()->withErrors(['date_debut' => 'Cette classe a deja une seance a cette date.'])->withInput();
        }

        // Verifier les conflits de professeur (en ignorant la seance actuelle)
        $professeurConflit = Seance::where('professeur_id', $request->professeur_id)
            ->where('id', '!=', $seance->id)
            ->where('date_debut', '<', $request->date_fin)
            ->where('date_fin', '>', $request->date_debut)
            ->exists();

        if ($professeurConflit) {
            return back()->withErrors(['professeur_id' => 'Ce professeur a deja une seance a ce moment.'])->withInput();
        }

        // Mettre a jour la seance
        $seance->update($request->only([
            'annee_classe_id',
            'matiere_id',
            'professeur_id',
            'statut_seance_id',
            'semestre_id',
            'type_seance_id',
            'date_debut',
            'date_fin',
            'date_reportee',
            'heure_debut_report',
            'heure_fin_report',
            'commentaire_report',
        ]));

        return redirect()->route('seances.index')->with('success', 'Seance mise a jour avec succes.');
    }

    public function destroy(Seance $seance)
    {
        // Supprimer la seance
        $seance->delete();
        return redirect()->route('seances.index')->with('success', 'Seance supprimee avec succes.');
    }
}

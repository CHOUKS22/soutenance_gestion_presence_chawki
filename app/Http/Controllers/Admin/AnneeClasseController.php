<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeClasse;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Coordinateur;
use Illuminate\Http\Request;

class AnneeClasseController extends Controller
{
    // Affiche la liste des associations annee-classe
    public function index()
    {
        $anneesClasses = AnneeClasse::with(['anneeAcademique', 'classe', 'coordinateur', 'etudiants'])
            ->orderBy('created_at', 'desc')->paginate(8);

        return view('admin.annees-classes.index', compact('anneesClasses'));
    }

    // Affiche les details d'une association annee-classe
    public function show(AnneeClasse $anneeClasse)
    {
        $anneeClasse->load(['anneeAcademique', 'classe', 'coordinateur.user', 'etudiants']);

        return view('admin.annees-classes.show', ['anneeClasse' => $anneeClasse]);
    }

    // Formulaire pour ajouter une nouvelle association
    public function create()
    {
        $anneesAcademiques = AnneeAcademique::orderBy('libelle', 'desc')->get();
        $classes = Classe::orderBy('nom')->get();
        $coordinateurs = Coordinateur::with('user')->get();

        return view('admin.annees-classes.create', compact('anneesAcademiques', 'classes', 'coordinateurs'));
    }

    // Enregistre une nouvelle association annee-classe
    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'classe_id' => 'required|exists:classes,id',
            'coordinateur_id' => 'required|exists:coordinateurs,id',
        ]);

        // Verifie si la combinaison annee + classe existe deja
        $exists = AnneeClasse::where('annee_academique_id', $validated['annee_academique_id'])
            ->where('classe_id', $validated['classe_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'classe_id' => 'Cette classe est deja associee a cette annee academique.'
            ])->withInput();
        }

        AnneeClasse::create($validated);

        return redirect()->route('annees-classes.index')->with('success', 'Association annee-classe creee avec succes.');
    }

    // Formulaire pour modifier une association existante
    public function edit(AnneeClasse $anneeClasse)
    {
        $anneesAcademiques = AnneeAcademique::orderBy('libelle', 'desc')->get();
        $classes = Classe::orderBy('nom')->get();
        $coordinateurs = Coordinateur::with('user')->get();

        return view('admin.annees-classes.edit', [
            'anneeClasse' => $anneeClasse,
            'anneesAcademiques' => $anneesAcademiques,
            'classes' => $classes,
            'coordinateurs' => $coordinateurs
        ]);
    }

    // Met a jour une association annee-classe
    public function update(Request $request, AnneeClasse $anneeClasse)
    {
        $validated = $request->validate([
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'classe_id' => 'required|exists:classes,id',
            'coordinateur_id' => 'required|exists:coordinateurs,id',
        ]);

        // Verifie si la combinaison existe deja sauf pour l'enregistrement actuel
        $exists = AnneeClasse::where('annee_academique_id', $validated['annee_academique_id'])
            ->where('classe_id', $validated['classe_id'])
            ->where('id', '!=', $anneeClasse->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'classe_id' => 'Cette classe est deja associee a cette annee academique.'
            ])->withInput();
        }

        $anneeClasse->update($validated);

        return redirect()->route('annees-classes.index')->with('success', 'Association annee-classe modifiee avec succes.');
    }

    // Supprime une association si aucun etudiant n'y est lie
    public function destroy(AnneeClasse $anneeClasse)
    {
        if ($anneeClasse->etudiants()->count() > 0) {
            return redirect()->route('annees-classes.index')->with('error', 'Impossible de supprimer cette association car elle contient des etudiants.');
        }

        $anneeClasse->delete();

        return redirect()->route('annees-classes.index')->with('success', 'Association annee-classe supprimee avec succes.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semestre;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class SemestreController extends Controller
{
    // Liste tous les semestres avec leur annee academique
    public function index()
    {
        $semestres = Semestre::with('anneeAcademique')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.semestres.index', compact('semestres'));
    }

    // Affiche les infos d'un semestre
    public function show(Semestre $semestre)
    {
        $semestre->load('anneeAcademique');
        return view('admin.semestres.show', compact('semestre'));
    }

    // Formulaire pour creer un semestre
    public function create()
    {
        $annees_academiques = AnneeAcademique::all();
        return view('admin.semestres.create', compact('annees_academiques'));
    }

    // Enregistrer un nouveau semestre
    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'libelle' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        Semestre::create($validated);

        return redirect()->route('semestres.index')->with('success', 'Semestre cree avec succes.');
    }

    // Formulaire de modification d'un semestre
    public function edit(Semestre $semestre)
    {
        $annees_academiques = AnneeAcademique::all();
        return view('admin.semestres.edit', compact('semestre', 'annees_academiques'));
    }

    // Mettre a jour un semestre
    public function update(Request $request, Semestre $semestre)
    {
        $validated = $request->validate([
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'libelle' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $semestre->update($validated);

        return redirect()->route('semestres.index')->with('success', 'Semestre modifie avec succes.');
    }

    // Supprimer un semestre
    public function destroy(Semestre $semestre)
    {
        $semestre->delete();
        return redirect()->route('semestres.index')->with('success', 'Semestre supprime avec succes.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneAcademiqueController extends Controller
{
    // Affiche la liste des annees academiques
    public function index()
    {
        $anneesAcademiques = AnneeAcademique::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.annees-academiques.index', compact('anneesAcademiques'));
    }

    // Montre le formulaire de creation
    public function create()
    {
        return view('admin.annees-academiques.create');
    }

    // Enregistre une nouvelle annee academique
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255|unique:annees_academiques',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        AnneeAcademique::create($data);

        return redirect()->route('annees-academiques.index')->with('success', 'Année académique créée avec succès.');
    }

    // Affiche les details d'une annee academique
    public function show(AnneeAcademique $anneeAcademique)
    {
        return view('admin.annees-academiques.show', compact('anneeAcademique'));
    }

    // Affiche le formulaire d'edition
    public function edit(AnneeAcademique $anneeAcademique)
    {
        return view('admin.annees-academiques.edit', compact('anneeAcademique'));
    }

    // Met a jour une annee academique
    public function update(Request $request, AnneeAcademique $anneeAcademique)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255|unique:annees_academiques,libelle,' . $anneeAcademique->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $anneeAcademique->update($data);

        return redirect()->route('annees-academiques.index')->with('success', 'Année académique modifiée avec succès.');
    }

    // Supprime une annee academique
    public function destroy(AnneeAcademique $anneeAcademique)
    {
        $anneeAcademique->delete();

        return redirect()->route('annees-academiques.index')->with('success', 'Année académique supprimée avec succès.');
    }
}

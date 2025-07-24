<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statut_seance;
use Illuminate\Http\Request;

class StatutSeanceController extends Controller
{
    // Liste les statuts de seance
    public function index()
    {
        $statutsSeances = Statut_seance::withCount('seances')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.statuts-seances.index', compact('statutsSeances'));
    }

    // Affiche les details d'un statut
    public function show(Statut_seance $gestionStatutSeance)
    {
        $gestionStatutSeance->load('seances');

        return view('admin.statuts-seances.show', [
            'statutSeance' => $gestionStatutSeance
        ]);
    }

    // Formulaire de creation
    public function create()
    {
        return view('admin.statuts-seances.statut-seance-create');
    }

    // Enregistre un nouveau statut
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:statuts_seances',
            'description' => 'nullable|string|max:1000',
        ]);

        Statut_seance::create($validated);

        return redirect()->route('statuts-seances.index')->with('success', 'Statut de seance cree avec succes.');
    }

    // Formulaire d'edition
    public function edit(Statut_seance $gestionStatutSeance)
    {
        return view('admin.statuts-seances.edit', [
            'statutSeance' => $gestionStatutSeance
        ]);
    }

    // Met a jour le statut
    public function update(Request $request, Statut_seance $gestionStatutSeance)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:statuts_seances,libelle,' . $gestionStatutSeance->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $gestionStatutSeance->update($validated);

        return redirect()->route('statuts-seances.index')->with('success', 'Statut de seance modifie avec succes.');
    }

    // Supprime le statut si non utilise
    public function destroy(Statut_seance $gestionStatutSeance)
    {
        if ($gestionStatutSeance->seances()->count() > 0) {
            return redirect()->route('statuts-seances.index')
                ->with('error', 'Impossible de supprimer ce statut car il est utilise.');
        }

        $gestionStatutSeance->delete();

        return redirect()->route('statuts-seances.index')->with('success', 'Statut de seance supprime avec succes.');
    }
}

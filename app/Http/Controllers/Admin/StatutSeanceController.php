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
    public function show(Statut_seance $statuts_seance)
    {
        $statuts_seance->load('seances');

        return view('admin.statuts-seances.show', [
            'statutSeance' => $statuts_seance
        ]);
    }

    // Formulaire de creation
    public function create()
    {
        return view('admin.statuts-seances.create');
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
    public function edit(Statut_seance $statuts_seance)
    {
        return view('admin.statuts-seances.edit', [
            'statutSeance' => $statuts_seance
        ]);
    }

    // Met a jour le statut
    public function update(Request $request, Statut_seance $statuts_seance)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:statuts_seances,libelle,' . $statuts_seance->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $statuts_seance->update($validated);

        return redirect()->route('statuts-seances.index')->with('success', 'Statut de séance modifié avec succès.');
    }

    // Supprime le statut si non utilise
    public function destroy(Statut_seance $statuts_seance)
    {
        if ($statuts_seance->seances()->count() > 0) {
            return redirect()->route('statuts-seances.index')
                ->with('error', 'Impossible de supprimer ce statut car il est utilisé.');
        }

        $statuts_seance->delete();

        return redirect()->route('statuts-seances.index')->with('success', 'Statut de séance supprimé avec succès.');
    }
}

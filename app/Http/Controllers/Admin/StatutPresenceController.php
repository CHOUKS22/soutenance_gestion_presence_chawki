<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statut_presence;
use Illuminate\Http\Request;

class StatutPresenceController extends Controller
{
    // Liste tous les statuts avec le nombre de presences
    public function index()
    {
        $statutsPresences = Statut_presence::withCount('presences')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.statuts-presences.index', compact('statutsPresences'));
    }

    // Affiche les infos d'un statut
    // public function show(Statut_presence $statuts_presence)
    // {
    //     $statuts_presence->load('presences');

    //     return view('admin.statuts-presences.show', [
    //         'statutPresence' => $statuts_presence
    //     ]);
    // }

    // Formulaire de creation
    public function create()
    {
        return view('admin.statuts-presences.create');
    }

    // Enregistre un nouveau statut
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:statuts_presences',
            'description' => 'nullable|string|max:1000',
        ]);

        Statut_presence::create($validated);

        return redirect()->route('statuts-presences.index')->with('success', 'Statut de presence cree avec succes.');
    }

    // Formulaire d'edition
    public function edit(Statut_presence $statuts_presence)
    {
        return view('admin.statuts-presences.edit', [
            'statutPresence' => $statuts_presence
        ]);
    }

    // Met a jour le statut
    public function update(Request $request, Statut_presence $statuts_presence)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:statuts_presences,libelle,' . $statuts_presence->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $statuts_presence->update($validated);

        return redirect()->route('statuts-presences.index')->with('success', 'Statut de presence modifie avec succes.');
    }

    // Supprime le statut si non utilise
    public function destroy(Statut_presence $statuts_presence)
    {
        if ($statuts_presence->presences()->count() > 0) {
            return redirect()->route('statuts-presences.index')
                ->with('error', 'Impossible de supprimer ce statut car il est utilise.');
        }

        $statuts_presence->delete();

        return redirect()->route('statuts-presences.index')->with('success', 'Statut de presence supprime avec succes.');
    }
}

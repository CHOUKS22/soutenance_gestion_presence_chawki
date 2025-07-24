<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type_seance;
use Illuminate\Http\Request;

class TypeSeanceController extends Controller
{
    // Liste des types de seance
    public function index()
    {
        $types_seances = Type_seance::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.types-seances.index', compact('types_seances'));
    }

    // // Details d'un type
    // public function show(Type_seance $type_seance)
    // {
    //     return view('admin.types-seances.show', compact('type_seance'));
    // }

    // Formulaire de creation
    public function create()
    {
        return view('admin.types-seances.create');
    }

    // Enregistrer un nouveau type
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:types_seances',
            'description' => 'required|string|max:1000',
        ]);

        Type_seance::create($validated);

        return redirect()->route('types-seances.index')->with('success', 'Type de seance cree avec succes.');
    }

    // Formulaire de modification
    public function edit(Type_seance $type_seance)
    {
        return view('admin.types-seances.edit', compact('type_seance'));
    }

    // Mettre a jour un type
    public function update(Request $request, Type_seance $type_seance)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:types_seances,nom,' . $type_seance->id,
            'description' => 'required|string|max:1000',
        ]);

        $type_seance->update($validated);

        return redirect()->route('types-seances.index')->with('success', 'Type de seance modifie avec succes.');
    }

    // Supprimer un type
    public function destroy(Type_seance $type_seance)
    {
        $type_seance->delete();
        return redirect()->route('types-seances.index')->with('success', 'Type de seance supprime avec succes.');
    }
}

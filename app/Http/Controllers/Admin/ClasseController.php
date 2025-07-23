<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    // Affiche toutes les classes avec pagination
    public function index()
    {
        $classes = Classe::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    // Affiche les details d'une classe
    public function show(Classe $classe)
    {
        return view('admin.classes.show', compact('classe'));
    }

    // Montre le formulaire pour ajouter une classe
    public function create()
    {
        return view('admin.classes.create');
    }

    // Enregistre une nouvelle classe
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:classes',
        ]);

        Classe::create($validated);

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès.');
    }

    // Montre le formulaire pour modifier une classe
    public function edit(Classe $classe)
    {
        return view('admin.classes.edit', compact('classe'));
    }

    // Met a jour une classe existante
    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:classes,nom,' . $classe->id,
        ]);

        $classe->update($validated);

        return redirect()->route('classes.index')->with('success', 'Classe modifiée avec succès.');
    }

    // Supprime une classe
    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès.');
    }
}

<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Justification;
use Illuminate\Support\Facades\Auth;

class JustificationController extends Controller
{
    public function index(Request $request)
    {
        // Absences non justifiees
        $absences = Absence::whereNotIn('id', function ($query) {
            $query->select('absence_id')->from('absence_justifie');
        })
        ->with([
            'etudiant.user',
            'seance.anneeClasse.classe',
            'seance.matiere'
        ])
        ->orderByDesc('created_at')
        ->paginate(10, ['*'], 'non_justifiees');

        // Absences deja justifiees
        $justifiees = Absence::whereIn('id', function ($query) {
            $query->select('absence_id')->from('absence_justifie');
        })
        ->with([
            'etudiant.user',
            'seance.anneeClasse.classe',
            'seance.matiere',
            'justifications'
        ])
        ->orderByDesc('created_at')
        ->paginate(10, ['*'], 'justifiees');

        // Envoyer les deux listes a la vue
        return view('coordinateur.justifications.index', [
            'absencesNonJustifiees' => $absences,
            'absencesJustifiees' => $justifiees,
        ]);
    }

    public function create($absence_id)
    {
        // Recuperer l'absence avec ses infos pour affichage
        $absence = Absence::with([
            'etudiant.user',
            'seance.anneeClasse.classe',
            'seance.matiere'
        ])->findOrFail($absence_id);

        return view('coordinateur.justifications.create', compact('absence'));
    }

    public function store(Request $request, $absence_id)
    {
        // Valider les donnees du formulaire
        $data = $request->validate([
            'motif' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Sauvegarder le fichier si present
        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('justifications', 'public');
        }

        // Creer une nouvelle justification
        $justification = Justification::create([
            'motif' => $data['motif'],
            'document' => $data['document'] ?? null,
            'saisie_par' => Auth::id(),
            'date_saisie' => now(),
        ]);

        // Lier la justification a l'absence
        $justification->absences()->attach($absence_id);

        return redirect()->route('justifications.index')->with('success', 'Justification enregistree.');
    }
}

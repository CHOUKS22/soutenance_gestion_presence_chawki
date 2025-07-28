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
        // Absences non encore justifiées
        $absences = Absence::whereNotIn('id', function ($query) {
            $query->select('absence_id')->from('absence_justifie');
        })
            ->with([
                'etudiant.user',
                'seance.anneeClasse.classe', // correction ici
                'seance.matiere'
            ])
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'non_justifiees');

        // Absences déjà justifiées
        $justifiees = Absence::whereIn('id', function ($query) {
            $query->select('absence_id')->from('absence_justifie');
        })
            ->with([
                'etudiant.user',
                'seance.anneeClasse.classe', // correction ici aussi
                'seance.matiere',
                'justifications'
            ])
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'justifiees');

        return view('coordinateur.justifications.index', [
            'absencesNonJustifiees' => $absences,
            'absencesJustifiees' => $justifiees,
        ]);
    }

    public function create($absence_id)
    {
        // On récupère l'absence avec ses relations pour l'afficher dans le formulaire
        $absence = Absence::with([
            'etudiant.user',
            'seance.anneeClasse.classe', // correction ici
            'seance.matiere'
        ])->findOrFail($absence_id);

        return view('coordinateur.justifications.create', compact('absence'));
    }

    public function store(Request $request, $absence_id)
    {
        // Validation
        $data = $request->validate([
            'motif' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Enregistrement du fichier si fourni
        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('justifications', 'public');
        }

        // Création de la justification
        $justification = Justification::create([
            'motif' => $data['motif'],
            'document' => $data['document'] ?? null,
            'saisie_par' => Auth::id(),
            'date_saisie' => now(),
        ]);

        // Association à l'absence
        $justification->absences()->attach($absence_id);

        return redirect()->route('justifications.index')->with('success', 'Justification enregistrée.');
    }
}

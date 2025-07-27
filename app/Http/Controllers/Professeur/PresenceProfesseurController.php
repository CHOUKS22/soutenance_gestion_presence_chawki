<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Statut_presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceProfesseurController extends Controller
{
    public function index($seanceId)
    {
        $seance = Seance::with(['classe.anneesClasses.etudiants'])
            ->findOrFail($seanceId);

        $etudiants = collect();
        foreach ($seance->classe->anneesClasses as $anneeClasse) {
            $etudiants = $etudiants->merge($anneeClasse->etudiants);
        }

        $etudiants = $etudiants->unique('id');
        $statutsPresence = Statut_presence::all();
        $presencesMarquees = Presence::where('seance_id', $seanceId)->get()->keyBy('etudiant_id');
        $absencesMarquees = Absence::where('seance_id', $seanceId)->get()->keyBy('etudiant_id');

        return view('professeur.presences.index', compact(
            'seance',
            'etudiants',
            'statutsPresence',
            'presencesMarquees',
            'absencesMarquees'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
            'statuts_presence_id' => 'required|exists:statuts_presences,id',
        ]);

        Presence::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
            ],
            [
                'statuts_presence_id' => $request->statuts_presence_id,
                'created_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'Présence enregistrée.');
    }
}

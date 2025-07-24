<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\StatutPresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'seance_id' => 'required|exists:seances,id',
            'statuts_presence_id' => 'required|exists:statuts_presences,id',
        ]);

        // Vérifier si une présence existe déjà pour cet étudiant et cette séance
        $existingPresence = Presence::where('etudiant_id', $request->etudiant_id)
            ->where('seance_id', $request->seance_id)
            ->first();

        if ($existingPresence) {
            // Mettre à jour le statut de présence existant
            $existingPresence->update([
                'statuts_presence_id' => $request->statuts_presence_id,
                'created_by' => Auth::id(),
            ]);

            $message = 'Statut de présence mis à jour avec succès.';
        } else {
            // Créer une nouvelle entrée de présence
            Presence::create([
                'etudiant_id' => $request->etudiant_id,
                'seance_id' => $request->seance_id,
                'statuts_presence_id' => $request->statuts_presence_id,
                'created_by' => Auth::id(),
            ]);

            $message = 'Présence enregistrée avec succès.';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function enregistrerTout(Request $request)
    {
        $request->validate([
            'seance_id' => 'required|exists:seances,id',
            'presences' => 'required|array',
            'presences.*.etudiant_id' => 'required|exists:etudiants,id',
            'presences.*.statuts_presence_id' => 'required|exists:statuts_presences,id',
        ]);

        $successCount = 0;

        foreach ($request->presences as $presenceData) {
            $existingPresence = Presence::where('etudiant_id', $presenceData['etudiant_id'])
                ->where('seance_id', $request->seance_id)
                ->first();

            if ($existingPresence) {
                $existingPresence->update([
                    'statuts_presence_id' => $presenceData['statuts_presence_id'],
                    'created_by' => Auth::id(),
                ]);
            } else {
                Presence::create([
                    'etudiant_id' => $presenceData['etudiant_id'],
                    'seance_id' => $request->seance_id,
                    'statuts_presence_id' => $presenceData['statuts_presence_id'],
                    'created_by' => Auth::id(),
                ]);
            }

            $successCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Présences enregistrées pour {$successCount} étudiant(s)."
        ]);
    }

    public function getPresencesForSeance($seanceId)
    {
        $presences = Presence::where('seance_id', $seanceId)
            ->with(['etudiant.user', 'statutPresence'])
            ->get()
            ->keyBy('etudiant_id');

        return response()->json($presences);
    }
}

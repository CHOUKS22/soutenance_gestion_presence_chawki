<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirige vers le dashboard approprié selon le rôle de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return redirect()->route('login');
        }

        switch ($user->role->nom) {
            case 'Administrateur':
                return redirect()->route('admin.dashboard');
            case 'Professeur':
                return redirect()->route('professeur.dashboard');
            case 'Coordinateur':
                return redirect()->route('coordinateur.dashboard');
            case 'Etudiant':
                return redirect()->route('etudiant.dashboard');
            case 'Parent':
                return redirect()->route('parent.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Rôle non reconnu');
        }
    }
}

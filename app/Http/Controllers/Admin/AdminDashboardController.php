<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $users = User::with('role')->latest()->paginate(8);
        //Compte les utilisateurs avec une methode plus rapide qui remplace whereas(utilise fonction flecher)
        $totalsUsers = User::count();
        $totalsEtudiants = User::whereRelation('role', 'nom', 'Etudiant')->count();
        $totalsProfesseurs = User::whereRelation('role', 'nom', 'Professeur')->count();
        $totalsCoordinateurs = User::whereRelation('role', 'nom', 'Coordinateur')->count();
        $totalsParents = User::whereRelation('role', 'nom', 'Parent')->count();
        $totalsAdmins = User::whereRelation('role', 'nom', 'Admin')->count();

        return view('admin.dashboard', compact(
            'user',
            'users',
            'totalsUsers',
            'totalsEtudiants',
            'totalsProfesseurs',
            'totalsCoordinateurs',
            'totalsParents',
            'totalsAdmins',
        ));
    }
}

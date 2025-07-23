<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardCoordinateurController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('coordinateur.dashboard', compact('user'));
    }
}

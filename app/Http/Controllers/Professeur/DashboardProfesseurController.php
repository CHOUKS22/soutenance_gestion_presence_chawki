<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardProfesseurController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('professeur.dashboard', compact('user'));
    }



}

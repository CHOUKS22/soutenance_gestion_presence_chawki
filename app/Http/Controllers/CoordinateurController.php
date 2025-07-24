<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinateurController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('coordinateur.dashboard', compact('user'));
    }
}

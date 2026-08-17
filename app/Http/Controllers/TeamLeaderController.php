<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamLeaderController extends Controller
{
    public function dashboard()
    {
        return view('teamleader.dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesPersonController extends Controller
{
    public function dashboard()
    {
        return view('sales.dashboard');
    }
}

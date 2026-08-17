<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'seller') {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.seller-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $user = Auth::user();

        // Sirf seller hi yahan login kar sakta hai
        if ($user->role !== 'seller') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'You do not have permission to login here.']);
        }

        $request->session()->regenerate();

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'Login',
            'module'      => 'Authentication',
            'description' => "Seller logged in: {$user->name}",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.dashboard');
    }
}
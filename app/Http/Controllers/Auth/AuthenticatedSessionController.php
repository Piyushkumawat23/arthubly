<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = $request->user();

    if ($user->role !== 'user') {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'You do not have permission to login here.',
        ]);
    }

    $request->session()->regenerate();

    // --- YAHAN CHANGE KAREIN ---
    // Agar form se hidden input 'redirect_to' aaya hai, toh wahan bheje
    if ($request->has('redirect_to')) {
        return redirect($request->redirect_to);
    }

    // Default behavior
    return redirect()->intended(route('dashboard', absolute: false));
}
    


    /**
     * Destroy an authenticated session.
     */
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->role !== 'user') {
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'Logout',
                'module'      => 'Authentication',
                'description' => ucfirst($user->role) . " logged out: {$user->name}",
                'ip_address'  => $request->ip(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

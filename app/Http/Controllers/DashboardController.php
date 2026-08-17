<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\SalesPersonController;
use App\Http\Controllers\TeamLeaderController;
use App\Http\Controllers\UserController;

class DashboardController extends Controller
{
    public function routeToDashboard($role)
    {
        // Security Check: Make sure the logged-in user actually has this role
        // Varna koi bhi normal user URL me /admin/dashboard likh kar access kar lega
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized access to this dashboard.');
        }

        // Match the role to the correct controller
        $controllerName = match ($role) {
            'admin' => AdminController::class,
            'subadmin' => SubAdminController::class,
            'salesperson' => SalesPersonController::class,
            'teamleader' => TeamLeaderController::class,
            default => UserController::class
        };

        // Dynamically call the 'dashboard' method of the matched controller
        return app()->call([$controllerName, 'dashboard']);
    }

    public function markNotificationsAsRead()
{
    // Sabhi unread notifications ko read mark kar dega
    auth()->user()->unreadNotifications->markAsRead();
    
    return redirect()->back()->with('success', 'All notifications marked as read.');
}
}
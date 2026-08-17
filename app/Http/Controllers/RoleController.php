<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // ==========================================
    // LIST ALL ROLES
    // ==========================================
    public function index()
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi system roles dekh sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to System Roles.');
        }

        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        // 🟢 SECURITY CHECK
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.roles.create');
    }

    // ==========================================
    // STORE NEW ROLE LOGIC
    // ==========================================
    public function store(Request $request)
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi naya role bana sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Roles',
            'description' => "Created new system access role: '{$role->name}'",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Role created successfully.');
    }
}
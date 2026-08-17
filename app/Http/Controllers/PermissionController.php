<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // ==========================================
    // LIST ALL PERMISSIONS
    // ==========================================
    public function index()
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi permissions matrix dekh sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to Security Permissions.');
        }

        $permissiones = Permission::all();
        return view('admin.permissions.index', compact('permissiones'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.permissions.create');
    }

    // ==========================================
    // STORE NEW PERMISSION LOGIC
    // ==========================================
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'module' => 'required',
            'action' => 'required',
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = Permission::create($request->only('module', 'action', 'name'));

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Permissions',
            'description' => "Created new security permission: '{$permission->name}' under module '{$permission->module}'",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.permissions.edit', compact('permission'));
    }

    // ==========================================
    // UPDATE PERMISSION LOGIC
    // ==========================================
    public function update(Request $request, Permission $permission)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'module' => 'required',
            'action' => 'required',
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        // 🟢 CAPTURE CHANGES FOR AUDIT TRAIL
        $permission->fill($request->only('module', 'action', 'name'));
        $changes = $permission->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $permission->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $permission->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Permissions',
                'description' => json_encode(['permission_name' => $permission->name, 'old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }
    
    // ==========================================
    // TOGGLE STATUS (AJAX) LOGIC
    // ==========================================
    public function toggleStatus(Permission $permission)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $oldStatus = $permission->is_active;
        $permission->is_active = !$permission->is_active;
        $permission->save();

        // 🟢 CREATE TOGGLE STATUS ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Toggle Status',
            'module' => 'Permissions',
            'description' => json_encode([
                'permission_name' => $permission->name,
                'old_status' => $oldStatus,
                'new_status' => $permission->is_active
            ]),
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $permission->is_active,
            'message' => 'Permission status updated successfully.'
        ]);
    }

    // ==========================================
    // DESTROY PERMISSION LOGIC
    // ==========================================
    public function destroy(Permission $permission)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG BEFORE DROPPING RECORDS
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Permissions',
            'description' => "Deleted security permission rule: '{$permission->name}'",
            'ip_address' => request()->ip(),
        ]);

        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
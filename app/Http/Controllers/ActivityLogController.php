<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Optional Filter by Module (e.g. ?module=Customers)
        if ($request->has('module') && $request->module != '') {
            $query->where('module', $request->module);
        }

        // Optional Filter by Action (e.g. ?action=Login)
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        // 50 items per page
        $logs = $query->paginate(50);
        
        return view('admin.logs.index', compact('logs'));
    }

    public function clear()
    {
        // Safety Check: Sirf main admin clear kar paye (if applicable)
        if(auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Only super admin can clear logs.');
        }

        ActivityLog::truncate();
        
        // Log the clearance action itself!
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'System Logs',
            'description' => "All previous system logs were cleared.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'All system logs have been cleared.');
    }
}
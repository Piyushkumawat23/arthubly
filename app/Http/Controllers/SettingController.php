<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        // First() use kar rahe hain kyunki settings ki hamesha ek hi row hogi
        $setting = Setting::first(); 
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico|max:1024',
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $data = $request->except(['logo', 'favicon', '_token']);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($setting->logo && File::exists(public_path('uploads/settings/' . $setting->logo))) {
                File::delete(public_path('uploads/settings/' . $setting->logo));
            }
            $logoName = 'logo_' . time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/settings/'), $logoName);
            $data['logo'] = $logoName;
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            if ($setting->favicon && File::exists(public_path('uploads/settings/' . $setting->favicon))) {
                File::delete(public_path('uploads/settings/' . $setting->favicon));
            }
            $faviconName = 'favicon_' . time() . '.' . $request->favicon->extension();
            $request->favicon->move(public_path('uploads/settings/'), $faviconName);
            $data['favicon'] = $faviconName;
        }

        $setting->fill($data);
        $setting->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update',
            'module' => 'Settings',
            'description' => "Updated website global settings",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
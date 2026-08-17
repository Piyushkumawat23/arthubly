<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // 🟢 B2B Panel Routing: Admin, Subadmin ya Seller ke liye admin layout wala edit view load hoga
        if (in_array($user->role, ['admin', 'subadmin', 'seller'])) {
            return view('admin.profile.edit', compact('user'));
        }

        // Normal customer ke liye frontend view
        return view('frontend.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $changes = $user->getDirty();
        
        if (!empty($changes)) {
            $newData = [];
            foreach ($changes as $key => $value) {
                $newData[$key] = $value;
            }

            if (in_array($user->role, ['admin', 'subadmin', 'seller'])) {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'Update',
                    'module' => 'Profile',
                    'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                    'ip_address' => $request->ip(),
                ]);
            }
        }

        $user->save();

        // 🟢 Panel validation message handling
        if (in_array($user->role, ['admin', 'subadmin', 'seller'])) {
            return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
        }

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if (in_array($user->role, ['admin', 'subadmin', 'seller'])) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Delete',
                'module' => 'Profile',
                'description' => "User deleted their own account: {$user->email}",
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
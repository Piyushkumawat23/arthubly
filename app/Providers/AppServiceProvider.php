<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Facades\Auth; // ✅ Add this import for Gates
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // --------------------------------------------------
        // ✅ NEW CODE FOR AUTHORIZATION GATES (START)
        // --------------------------------------------------

        // 1. Admin (Super User) को सारी permissions दें (Gate::before)
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });

        // 2. Dynamic Permissions Gates Define करें
        // Note: Production में Performance के लिए permissions को cache करना बेहतर होता है
        try {
            // Check if the permissions table exists before querying
            if (\Schema::hasTable('permissions')) {
                $permissions = Permission::all();

                foreach ($permissions as $permission) {
                    Gate::define($permission->module.'.'.$permission->action, function ($user) use ($permission) {
                        $role = Role::where('name', $user->role)->first();
                        if (! $role) {
                            return false;
                        }

                        // Role के पास वह specific permission है या नहीं, चेक करें (using the id is generally more robust)
                        return $role->permissions->contains('id', $permission->id);
                    });
                }
            }
        } catch (\Exception $e) {

        }

        // --------------------------------------------------
        // ✅ NEW CODE FOR AUTHORIZATION GATES (END)
        // --------------------------------------------------

        View::composer('*', function ($view) {
            $user = Auth::user();
            $globalPermissions = [];
            $setting = null;
            if ($user) {
                $roleName = $user->role;
                $role = Role::where('name', $roleName)->first();

                $globalPermissions = $role
                ? $role->permissions->map(function ($perm) {
                    return $perm->module.'.'.$perm->action;
                })->toArray()
                : [];
            }
            try {
                $setting = Setting::first();
            } catch (\Exception $e) {
                // Ignore agar table nahi hai
            }
            // Pass to all views
            $view->with([
                'user' => $user,
                'globalPermissions' => $globalPermissions,
                'setting' => $setting,
            ]);
        });

        $frontMenus = Menu::where('status', 1)
            ->whereNull('parent_id')
            ->with('children.children') // Admin me jaise banaya tha
            ->orderBy('order')
            ->get();

        // Ise har view ke liye globally share karein
        View::share('frontMenus', $frontMenus);

            $navCategories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        View::share('navCategories', $navCategories);
    }
}

<?php
/* ======================================================================
   LOGIN HOOK — guest wishlist ho to "merge?" flag set karo
   File: app/Providers/AppServiceProvider.php

   Apne AppServiceProvider ke boot() me ye code add karein.
   (use statements file ke top par le jaayein.)
   ====================================================================== */

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /* ---------- ye hissa add karein ---------- */

        // Login hote hi: agar guest ne kuch wishlist kiya tha, to poochho
        Event::listen(Login::class, function (Login $event) {
            $ids = session('guest_wishlist', []);

            if (is_array($ids) && count($ids) > 0) {
                session()->put('wishlist_merge_ask', true);
            } else {
                session()->forget(['guest_wishlist', 'wishlist_merge_ask']);
            }
        });

        // Logout par guest wishlist saaf — warna agle guest ko dikh jaayegi
        Event::listen(Logout::class, function () {
            session()->forget(['guest_wishlist', 'wishlist_merge_ask']);
        });

        /* ---------- yahan tak ---------- */
    }
}

/* ======================================================================
   NOTE — session regenerate
   Laravel login par session()->regenerate() karta hai, lekin regenerate
   sirf session ID badalta hai, DATA barkarar rehta hai. Isliye
   guest_wishlist login ke baad bhi available rehti hai. Kuch extra
   karne ki zaroorat nahi.
   ====================================================================== */

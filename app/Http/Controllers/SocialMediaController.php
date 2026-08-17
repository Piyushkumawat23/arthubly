<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai

class SocialMediaController extends Controller
{
    // ==========================================
    // SHOW CREATE FORM
    // ==========================================
    public function create()
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi social media automated tool access kar sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to Global Social Media Automation.');
        }

        return view('admin.social.create'); 
    }

    // ==========================================
    // STORE & PUBLISH TO META GRAPH API
    // ==========================================
    public function store(Request $request)
    {
        // 🟢 SECURITY CHECK
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'message' => 'required|string',
            'image_url' => 'required|url',
            'platforms' => 'required|array'
        ]);

        $message = $request->input('message');
        $imageUrl = $request->input('image_url');
        $platforms = $request->input('platforms');
        
        $fbPageId = env('FB_PAGE_ID');
        $igAccountId = env('IG_ACCOUNT_ID');
        $accessToken = env('META_ACCESS_TOKEN');

        if (!$fbPageId || !$accessToken) {
            return redirect()->back()->with('error', 'Meta API credentials .env file mein missing hain!');
        }

        try {
            // 1. Facebook Par Post Karna
            if (in_array('facebook', $platforms)) {
                $fbResponse = Http::post("https://graph.facebook.com/v19.0/{$fbPageId}/photos", [
                    'url' => $imageUrl,
                    'message' => $message,
                    'access_token' => $accessToken,
                ]);

                if ($fbResponse->failed()) {
                    throw new \Exception("Facebook API Error: " . $fbResponse->json('error.message', 'Unknown Facebook Error'));
                }
            }

            // 2. Instagram Par Post Karna
            if (in_array('instagram', $platforms)) {
                if (!$igAccountId) {
                    throw new \Exception("Instagram Account ID .env mein missing hai!");
                }

                // Step A: Create Media Container
                $containerResponse = Http::post("https://graph.facebook.com/v19.0/{$igAccountId}/media", [
                    'image_url' => $imageUrl,
                    'caption' => $message,
                    'access_token' => $accessToken,
                ]);
                
                if ($containerResponse->failed()) {
                    throw new \Exception("Instagram Container Error: " . $containerResponse->json('error.message', 'Unknown IG Error'));
                }

                $creationId = $containerResponse->json()['id'] ?? null;

                // Step B: Publish the Container
                if ($creationId) {
                    $publishResponse = Http::post("https://graph.facebook.com/v19.0/{$igAccountId}/media_publish", [
                        'creation_id' => $creationId,
                        'access_token' => $accessToken,
                    ]);

                    if ($publishResponse->failed()) {
                        throw new \Exception("Instagram Publish Error: " . $publishResponse->json('error.message', 'Unknown Publish Error'));
                    }
                }
            }

            // 🟢 CREATE SUCCESS ACTIVITY LOG
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Publish Social Post',
                'module' => 'Social Media',
                'description' => "Successfully published post on platforms: " . implode(', ', $platforms) . ". Message: " . Str::limit($message, 50),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->back()->with('success', 'Post successfully published on selected platforms!');

        } catch (\Exception $e) {
            
            // 🟢 CREATE FAILED ACTIVITY LOG FOR AUDIT
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Social Post Failed',
                'module' => 'Social Media',
                'description' => "Failed to publish on " . implode(', ', $platforms) . ". Error: " . $e->getMessage(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
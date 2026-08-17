<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🟢 B2B SELLER LOGIC: Seller ke liye uska specific folder
        $vendorFolder = ($user->role === 'seller') ? 'vendor_'.$user->id : '';
        $directory = public_path('uploads/'.$vendorFolder);

        $files = [];

        if (File::exists($directory)) {
            $allFiles = File::allFiles($directory);

            foreach ($allFiles as $file) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

                if (in_array(strtolower($file->getExtension()), $allowedExtensions)) {

                    $relativePath = str_replace('\\', '/', $file->getRelativePathname());

                    // 🟢 URL structure updated for vendor folder
                    $folderPrefix = ($user->role === 'seller') ? 'public/uploads/vendor_'.$user->id.'/' : 'public/uploads/';
                    $assetUrl = asset($folderPrefix.$relativePath);

                    $files[] = [
                        'name' => $file->getFilename(),
                        'url' => $assetUrl,
                        'full_path' => $file->getRealPath(),
                        'size' => number_format($file->getSize() / 1024, 2).' KB',
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                        'folder' => basename($file->getPath()),
                    ];
                }
            }
        }

        // Sort by newest first
        usort($files, function ($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });

        return view('admin.media.index', compact('files'));
    }

    public function destroy(Request $request)
    {
        $path = $request->file_path;
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK:
        // Ensure kijiye ki path mein user ka folder hai, agar seller delete kar raha hai
        if ($user->role === 'seller') {
            $vendorFolder = 'vendor_'.$user->id;
            if (strpos($path, $vendorFolder) === false) {
                return response()->json(['success' => false, 'message' => 'Unauthorized deletion!'], 403);
            }
        }

        if (File::exists($path)) {
            File::delete($path);

            return response()->json(['success' => true, 'message' => 'Image deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'File not found!'], 404);
    }
}

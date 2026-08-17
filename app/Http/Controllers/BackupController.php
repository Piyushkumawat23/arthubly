<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\ActivityLog;

class BackupController extends Controller
{
    public function index()
    {
        $path = storage_path('app/backups');
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $files = File::files($path);
        
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => number_format($file->getSize() / 1048576, 2) . ' MB',
                'date' => date("d M Y, h:i A", $file->getMTime()),
                'path' => $file->getRealPath(),
            ];
        }

        $backups = array_reverse($backups);
        return view('admin.backups.index', compact('backups'));
    }

    public function generate()
    {
        try {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', '127.0.0.1');

            $path = storage_path('app/backups');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $fileName = 'db_backup_' . date('Y_m_d_His') . '.sql';
            $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

            $passwordStr = empty($password) ? '' : "--password=\"{$password}\"";
            
            // Step 1: Env variable se path check karein (Agar user ne define kiya ho)
            $mysqldumpPath = env('MYSQLDUMP_PATH', 'mysqldump'); 

            // Step 2: Agar Windows hai, toh common paths auto-search karein
            if ($mysqldumpPath === 'mysqldump' && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $commonPaths = [
                    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                    'D:\\xampp\\mysql\\bin\\mysqldump.exe',
                    'E:\\xampp\\mysql\\bin\\mysqldump.exe',
                    'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
                    'C:\\wamp64\\bin\\mariadb\\mariadb10.4.10\\bin\\mysqldump.exe'
                ];
                
                foreach ($commonPaths as $cp) {
                    if (File::exists($cp)) {
                        $mysqldumpPath = '"' . $cp . '"'; // Quotes me wrap karna zaroori hai Windows ke liye
                        break;
                    }
                }
            }

            // Agar path me space hai, to usey quotes me wrap karna zaroori hai Windows par
            $command = "\"{$mysqldumpPath}\" --user=\"{$username}\" {$passwordStr} --host=\"{$host}\" \"{$database}\" > \"{$filePath}\" 2>&1";
            // Command Run karein
            exec($command, $output, $returnVar);

            // Agar error aaye, toh actual command aur error message throw karein
            if ($returnVar !== 0) {
                $errorMsg = implode(" | ", $output);
                throw new \Exception("DUMP ERROR: " . $errorMsg . " || COMMAND TRIED: " . $command);
            }
            
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Backup',
                'module' => 'System',
                'description' => "Generated a new database backup: {$fileName}",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->back()->with('success', 'Backup generated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function download($fileName)
    {
        $file = storage_path('app/backups/' . $fileName);
        if (File::exists($file)) {
            return response()->download($file);
        }
        return redirect()->back()->with('error', 'File not found!');
    }

    public function destroy($fileName)
    {
        $file = storage_path('app/backups/' . $fileName);
        if (File::exists($file)) {
            File::delete($file);
            return redirect()->back()->with('success', 'Backup deleted successfully!');
        }
        return redirect()->back()->with('error', 'File not found!');
    }
}
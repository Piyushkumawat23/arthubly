<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SmtpSetting;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    // ==========================================
    // SMTP SETTINGS VIEW
    // ==========================================
    public function smtpSettings()
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi global SMTP dekh sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to Global SMTP Settings.');
        }

        $smtp = SmtpSetting::first();
        return view('admin.smtp_settings', compact('smtp'));
    }

    // ==========================================
    // UPDATE SMTP SETTINGS LOGIC
    // ==========================================
    public function updateSmtpSettings(Request $request)
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi global SMTP update kar sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'mailer' => 'required',
            'host' => 'required',
            'port' => 'required|integer',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'nullable',
            'from_address' => 'required|email',
            'from_name' => 'required',
        ]);

        $smtp = SmtpSetting::find(1);
        $oldData = $smtp ? $smtp->only(['mailer', 'host', 'port', 'username', 'encryption', 'from_address', 'from_name']) : [];

        // Ensure that only one record exists
        $smtp = SmtpSetting::updateOrCreate(
            ['id' => 1],
            $request->only(['mailer', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'])
        );

        $newData = $smtp->only(['mailer', 'host', 'port', 'username', 'encryption', 'from_address', 'from_name']);

        // 🟢 CREATE UPDATE ACTIVITY LOG (Password ko security reasons se log nahi kiya)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update',
            'module' => 'SMTP Settings',
            'description' => json_encode(['old' => $oldData, 'new' => $newData]),
            'ip_address' => $request->ip(),
        ]);

        // Update .env file
        $this->updateEnv([
            'MAIL_MAILER' => $smtp->mailer,
            'MAIL_HOST' => $smtp->host,
            'MAIL_PORT' => $smtp->port,
            'MAIL_USERNAME' => $smtp->username,
            'MAIL_PASSWORD' => $smtp->password,
            'MAIL_ENCRYPTION' => $smtp->encryption,
            'MAIL_FROM_ADDRESS' => $smtp->from_address,
            'MAIL_FROM_NAME' => $smtp->from_name,
        ]);

        // Config clear
        // Artisan::call('config:clear');

        return redirect()->back()->with('success', 'SMTP settings updated successfully!');
    }

    private function updateEnv($data)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
        }

        file_put_contents($envPath, $envContent);
    }

    // ==========================================
    // TEST SMTP CONFIGURATION LOGIC
    // ==========================================
    public function testSmtp(Request $request)
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi SMTP test kar sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email to verify SMTP settings.', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('SMTP Test Email');
            });

            // 🟢 CREATE TEST EMAIL SUCCESS LOG
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Test Email Sent',
                'module' => 'SMTP Settings',
                'description' => "Successfully sent SMTP test email to: {$request->test_email}",
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Test email sent successfully! Please check your inbox.');
        } catch (\Exception $e) {
            
            // 🟢 CREATE TEST EMAIL FAILED LOG
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Test Email Failed',
                'module' => 'SMTP Settings',
                'description' => "Failed to send test email to {$request->test_email}. Error: " . $e->getMessage(),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('error', 'Failed to send test email. Error: ' . $e->getMessage());
        }
    }
}
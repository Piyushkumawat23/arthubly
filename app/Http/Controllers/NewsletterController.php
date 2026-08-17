<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Models\ActivityLog; // 🟢 FIX: ActivityLog model import kiya
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    // Show all subscribers
    public function index()
    {
        $subscribers = Newsletter::latest()->paginate(10);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    // Store new subscription (Admin side)
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        $subscriber = Newsletter::create([
            'email' => $request->email,
            'status' => 'subscribed'
        ]);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id() ?? null, // Agar koi guest subscribe kare toh null jayega
            'action' => 'Create',
            'module' => 'Newsletter',
            'description' => "Added new subscriber: {$subscriber->email}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Subscribed Successfully!');
    }

    // Unsubscribe a user
    public function unsubscribe($id)
    {
        $subscriber = Newsletter::findOrFail($id);
        $subscriber->update(['status' => 'unsubscribed']);

        // 🟢 UNSUBSCRIBE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id() ?? null,
            'action' => 'Unsubscribe',
            'module' => 'Newsletter',
            'description' => "User Unsubscribed: {$subscriber->email}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'User Unsubscribed!');
    }

    public function edit($id)
    {
        $subscriber = Newsletter::findOrFail($id);
        return view('admin.newsletter.edit', compact('subscriber'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email,' . $id,
            'status' => 'required|in:subscribed,unsubscribed'
        ]);
    
        $subscriber = Newsletter::findOrFail($id);

        // Changes capture karne ke liye pehle data fill karenge
        $subscriber->fill([
            'email' => $request->email,
            'status' => $request->status
        ]);

        // 🟢 CAPTURE CHANGES FOR ACTIVITY LOG
        $changes = $subscriber->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $subscriber->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $subscriber->save();
    
        // 🟢 UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Newsletter',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('newsletter.index')->with('success', 'Subscriber updated successfully!');
    }
    
    // Delete a subscriber
    public function destroy($id)
    {
        $subscriber = Newsletter::findOrFail($id);

        // 🟢 DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Newsletter',
            'description' => "Deleted subscriber: {$subscriber->email}",
            'ip_address' => request()->ip(),
        ]);

        $subscriber->delete();

        return redirect()->back()->with('success', 'Subscriber Deleted!');
    }

    public function showindex()
    {
        $subscribers = Newsletter::latest()->paginate(10);
        return view('admin.newsletter.newsletter', compact('subscribers'));
    }

    public function sendNewsletter(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $subscribers = Newsletter::where('status', 'subscribed')->pluck('email');

        foreach ($subscribers as $email) {
            Mail::send('emails.newsletter', [
                'subject' => $request->subject,
                'messageContent' => $request->message
            ], function ($message) use ($email, $request) {
                $message->to($email)
                        ->subject($request->subject);
            });
        }

        // 🟢 EMAIL SENT ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Send Newsletter',
            'module' => 'Newsletter',
            'description' => "Mass newsletter sent to " . $subscribers->count() . " subscribers. Subject: '{$request->subject}'",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Newsletter Sent Successfully!');
    }
}
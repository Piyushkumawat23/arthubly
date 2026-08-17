<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // List Notes (Filtered by Role)
    public function index()
    {
        $user = auth()->user();
        $query = Note::latest();

        // 🟢 B2B SELLER LOGIC: Seller sirf apne notes dekh sake
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $notes = $query->get();
        return view('admin.notes.index', compact('notes')); 
    }

    public function create()
    {
        return view('admin.notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);

        $data = $request->all();
        // 🟢 B2B SELLER LOGIC: Note save karte waqt owner assign karein
        $data['user_id'] = auth()->id();

        $note = Note::create($data);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Notes',
            'description' => "Created a new personal note: {$note->title}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.notes.index')->with('success', 'Note created successfully.');
    }

    public function edit($id)
    {
        $note = Note::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $note->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.notes.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);

        $note = Note::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $note->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CAPTURE CHANGES FOR ACTIVITY LOG
        $note->fill($request->all());
        $changes = $note->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $note->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $note->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Notes',
                'description' => json_encode(['note_title' => $note->title, 'old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $note->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Notes',
            'description' => "Deleted note: {$note->title}",
            'ip_address' => request()->ip(),
        ]);

        $note->delete();

        return redirect()->route('admin.notes.index')->with('success', 'Note deleted successfully.');
    }
}
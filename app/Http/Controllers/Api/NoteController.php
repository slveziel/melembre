<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
            'voice_note' => 'nullable'
        ]);

        $note = Auth::user()->notes()->create($data);

        return response()->json($note, 201);
    }

    public function show(Note $note)
    {
        $this->authorizeNote($note);
        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
            'voice_note' => 'nullable'
        ]);

        $note->update($data);

        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        $this->authorizeNote($note);

        if ($note->voice_note) {
            Storage::delete('public/voice/' . $note->voice_note);
        }

        $note->delete();

        return response()->json(['message' => 'Excluído']);
    }

    public function uploadVoice(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $request->validate([
            'voice' => 'required|file|mimes:webm,ogg,mp3,wav|max:5120'
        ]);

        if ($note->voice_note) {
            Storage::delete('public/voice/' . $note->voice_note);
        }

        $file = $request->file('voice');
        $filename = 'voice_' . $note->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/voice', $filename);

        $note->update(['voice_note' => $filename]);

        return response()->json(['success' => true, 'filename' => $filename]);
    }

    protected function authorizeNote(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
    }
}

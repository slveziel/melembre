<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->latest()->paginate(10);
        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
            'voice_note' => 'nullable'
        ]);

        Auth::user()->notes()->create($data);

        return redirect()->route('notes.index')->with('success', 'Anotação criada!');
    }

    public function edit(Note $note)
    {
        $this->authorizeNote($note);
        return view('notes.edit', compact('note'));
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

        return redirect()->route('notes.index')->with('success', 'Anotação atualizada!');
    }

    public function destroy(Note $note)
    {
        $this->authorizeNote($note);

        // Delete voice file if exists
        if ($note->voice_note && Storage::exists('public/voice/' . $note->voice_note)) {
            Storage::delete('public/voice/' . $note->voice_note);
        }

        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Anotação excluída!');
    }

    public function uploadVoice(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $request->validate([
            'voice' => 'required|file|mimes:webm,ogg,mp3,wav|max:5120' // max 5MB
        ]);

        // Delete old voice note if exists
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

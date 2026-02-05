@extends('app')

@section('title', 'Anotações - melembre')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h1>Minhas Anotações</h1>
    <a href="{{ route('notes.create') }}" class="btn">Nova Anotação</a>
</div>

@if($notes->count() > 0)
    @foreach($notes as $note)
        <div class="card note">
            <div class="note-header">
                <div>
                    <div class="note-title">{{ $note->title }}</div>
                    <div class="note-date">{{ $note->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="actions">
                    <a href="{{ route('notes.edit', $note) }}" class="btn">Editar</a>
                    <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Excluir?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
            @if($note->content)
                <div class="note-content markdown-content">{{ $note->content }}</div>
            @endif
            @if($note->voice_note)
                <div style="margin-top: 1rem; border-top: 1px solid #eee; padding-top: 1rem;">
                    <strong>🎙️ Nota de voz:</strong>
                    <audio controls src="{{ asset('storage/voice/' . $note->voice_note) }}" style="width: 100%; margin-top: 0.5rem;"></audio>
                </div>
            @endif
        </div>
    @endforeach

    {{ $notes->links() }}
@else
    <div class="card empty">
        <p>Nenhuma anotação ainda.</p>
        <p><a href="{{ route('notes.create') }}">Crie sua primeira anotação</a></p>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.markdown-content').forEach(function(el) {
            el.innerHTML = marked.parse(el.textContent);
        });
    });
</script>
<style>
    .markdown-content h1 { font-size: 1.5rem; margin: 1rem 0 0.5rem; }
    .markdown-content h2 { font-size: 1.3rem; margin: 1rem 0 0.5rem; }
    .markdown-content h3 { font-size: 1.1rem; margin: 0.75rem 0 0.5rem; }
    .markdown-content ul, .markdown-content ol { margin-left: 1.5rem; margin-bottom: 0.5rem; }
    .markdown-content li { margin-bottom: 0.25rem; }
    .markdown-content p { margin-bottom: 0.5rem; }
    .markdown-content code { background: #f0f0f0; padding: 0.1rem 0.3rem; border-radius: 3px; font-size: 0.9em; }
    .markdown-content pre { background: #f0f0f0; padding: 0.75rem; border-radius: 4px; overflow-x: auto; margin: 0.5rem 0; }
    .markdown-content blockquote { border-left: 3px solid #ddd; margin: 0.5rem 0; padding-left: 1rem; color: #666; }
    .markdown-content a { color: #333; text-decoration: underline; }
    .markdown-content strong { font-weight: 600; }
</style>
@endsection

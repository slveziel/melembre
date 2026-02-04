@extends('layouts.app')

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
                    <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Excluir?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
            @if($note->content)
                <div class="note-content">{{ $note->content }}</div>
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
@endsection

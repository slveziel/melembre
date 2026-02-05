@extends('app')

@section('title', 'Editar Anotação - melembre')

@section('content')
<div class="card">
    <h1>Editar Anotação</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('notes.update', $note) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" id="title" name="title" required value="{{ old('title', $note->title) }}">
        </div>

        <div class="form-group">
            <label>Conteúdo (Markdown)</label>
            <div class="editor-container">
                <textarea id="markdown-editor" name="content">{{ old('content', $note->content) }}</textarea>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn">Salvar</button>
            <a href="{{ route('notes.index') }}" class="btn" style="background: #666;">Cancelar</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new EasyMDE({ 
            element: document.getElementById('markdown-editor'),
            spellChecker: false,
            status: false,
            forceSync: true
        });
    });
</script>
@endsection

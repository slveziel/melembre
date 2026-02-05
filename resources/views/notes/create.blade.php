@extends('app')

@section('title', 'Nova Anotação - melembre')

@section('content')
<div class="card">
    <h1>Nova Anotação</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('notes.store') }}">
        @csrf

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" id="title" name="title" required placeholder="Digite o título..." value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label>Conteúdo (Markdown)</label>
            <div class="editor-container">
                <textarea id="markdown-editor" name="content" placeholder="Digite sua anotação em Markdown...">{{ old('content') }}</textarea>
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
            placeholder: 'Digite sua anotação em Markdown...',
            status: false,
            forceSync: true
        });
    });
</script>
@endsection

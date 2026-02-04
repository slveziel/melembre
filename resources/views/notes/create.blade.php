@extends('layouts.app')

@section('title', 'Nova Anotação - melembre')

@section('content')
<div class="card">
    <h1>Nova Anotação</h1>

    <form method="POST" action="{{ route('notes.store') }}" id="noteForm">
        @csrf

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" id="title" name="title" required placeholder="Digite o título..." value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label for="content">Conteúdo</label>
            <textarea id="content" name="content" placeholder="Digite sua anotação...">{{ old('content') }}</textarea>
        </div>

        <!-- Voice Recording -->
        <div class="form-group">
            <label>Nota de Voz (opcional)</label>
            <div class="voice-recorder">
                <button type="button" id="recordBtn" class="voice-btn" style="background: #6c757d;">
                    <svg viewBox="0 0 24 24"><path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85C16.52 14.2 14.47 16 12 16s-4.52-1.8-4.93-4.15c-.08-.49-.49-.85-.98-.85s-.9.36-.98.85C6.52 17.2 8.47 19 12 19s4.52-1.8 4.93-4.15c.08-.49.49-.85.98-.85zM12 19c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
                </button>
                <div class="voice-status" id="voiceStatus">Clique para gravar</div>
                <div class="voice-player" id="voicePlayer" style="display: none;">
                    <audio id="audioPreview" controls></audio>
                    <div class="voice-actions">
                        <button type="button" class="btn btn-danger" id="deleteVoice">Excluir</button>
                    </div>
                </div>
                <input type="hidden" name="voice_note" id="voiceNoteInput">
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn">Salvar</button>
            <a href="{{ route('notes.index') }}" class="btn" style="background: #666;">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let mediaRecorder;
let audioChunks = [];
let isRecording = false;
let audioBlob = null;

const recordBtn = document.getElementById('recordBtn');
const voiceStatus = document.getElementById('voiceStatus');
const voicePlayer = document.getElementById('voicePlayer');
const audioPreview = document.getElementById('audioPreview');
const deleteVoice = document.getElementById('deleteVoice');
const voiceNoteInput = document.getElementById('voiceNoteInput');

recordBtn.addEventListener('click', async () => {
    if (!isRecording) {
        // Start recording
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = event => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPreview.src = audioUrl;
                voicePlayer.style.display = 'block';
                voiceStatus.textContent = 'Gravação salva! Clique para regravar';
            };

            mediaRecorder.start();
            isRecording = true;
            recordBtn.classList.add('recording');
            recordBtn.innerHTML = '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>';
        } catch (err) {
            voiceStatus.textContent = 'Erro: Permissão de microfone negada';
        }
    } else {
        // Stop recording
        mediaRecorder.stop();
        isRecording = false;
        recordBtn.classList.remove('recording');
        recordBtn.innerHTML = '<svg viewBox="0 0 24 24"><path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85C16.52 14.2 14.47 16 12 16s-4.52-1.8-4.93-4.15c-.08-.49-.49-.85-.98-.85s-.9.36-.98.85C6.52 17.2 8.47 19 12 19s4.52-1.8 4.93-4.15c.08-.49.49-.85.98-.85zM12 19c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>';

        // Prepare file for upload
        const formData = new FormData();
        formData.append('voice', audioBlob, 'recording.webm');
    }
});

deleteVoice.addEventListener('click', () => {
    audioBlob = null;
    voicePlayer.style.display = 'none';
    voiceNoteInput.value = '';
    voiceStatus.textContent = 'Clique para gravar';
});

// Update form submit to include audio file
document.getElementById('noteForm').addEventListener('submit', async (e) => {
    if (audioBlob) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('voice', audioBlob, 'recording.webm');

        // Upload to server and get filename
        try {
            const response = await fetch('/notes/0/voice', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                voiceNoteInput.value = data.filename;
                e.target.submit();
            }
        } catch (err) {
            console.error('Error uploading voice:', err);
            alert('Erro ao enviar áudio');
        }
    }
});
</script>
@endpush
@endsection

import { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import api from '../services/api';

function NoteForm() {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const navigate = useNavigate();

  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [voiceNote, setVoiceNote] = useState('');
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  // Voice recording
  const [isRecording, setIsRecording] = useState(false);
  const [voiceStatus, setVoiceStatus] = useState('Clique para gravar');
  const [audioBlob, setAudioBlob] = useState(null);
  const mediaRecorderRef = useRef(null);
  const audioChunksRef = useRef([]);

  useEffect(() => {
    if (isEdit) {
      fetchNote();
    }
  }, [id]);

  const fetchNote = async () => {
    setLoading(true);
    try {
      const response = await api.get(`/notes/${id}`);
      const note = response.data;
      setTitle(note.title);
      setContent(note.content || '');
      setVoiceNote(note.voice_note || '');
    } catch (err) {
      setError('Erro ao carregar anotação');
    } finally {
      setLoading(false);
    }
  };

  const handleVoiceRecord = async () => {
    if (!isRecording) {
      // Start recording
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorderRef.current = new MediaRecorder(stream);
        audioChunksRef.current = [];

        mediaRecorderRef.current.ondataavailable = (event) => {
          audioChunksRef.current.push(event.data);
        };

        mediaRecorderRef.current.onstop = () => {
          const blob = new Blob(audioChunksRef.current, { type: 'audio/webm' });
          setAudioBlob(blob);
          setVoiceStatus('Gravação salva! Clique para regravar');
        };

        mediaRecorderRef.current.start();
        setIsRecording(true);
        setVoiceStatus('Gravando...');
      } catch (err) {
        setVoiceStatus('Erro: Permissão de microfone negada');
      }
    } else {
      // Stop recording
      mediaRecorderRef.current?.stop();
      setIsRecording(false);
    }
  };

  const deleteVoice = () => {
    setAudioBlob(null);
    setVoiceNote('');
    setVoiceStatus('Clique para gravar');
  };

  const uploadVoice = async () => {
    if (!audioBlob) return null;

    const formData = new FormData();
    formData.append('voice', audioBlob, 'recording.webm');

    try {
      const response = await api.post(`/notes/${id || 0}/voice`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      return response.data.filename;
    } catch (err) {
      console.error('Error uploading voice:', err);
      return null;
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSaving(true);

    try {
      let voiceFilename = voiceNote;

      // Upload new voice if recorded
      if (audioBlob) {
        const uploaded = await uploadVoice();
        if (uploaded) {
          voiceFilename = uploaded;
        }
      }

      const data = {
        title,
        content,
        voice_note: voiceFilename
      };

      if (isEdit) {
        await api.put(`/notes/${id}`, data);
      } else {
        await api.post('/notes', data);
      }

      navigate('/notes');
    } catch (err) {
      setError(err.response?.data?.message || 'Erro ao salvar anotação');
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="loading">Carregando...</div>;
  }

  return (
    <div className="card">
      <h1>{isEdit ? 'Editar Anotação' : 'Nova Anotação'}</h1>

      {error && <div className="alert alert-danger">{error}</div>}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="title">Título</label>
          <input
            type="text"
            id="title"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            required
            placeholder="Digite o título..."
          />
        </div>

        <div className="form-group">
          <label htmlFor="content">Conteúdo</label>
          <textarea
            id="content"
            value={content}
            onChange={(e) => setContent(e.target.value)}
            placeholder="Digite sua anotação..."
          />
        </div>

        {/* Voice Recording */}
        <div className="form-group">
          <label>Nota de Voz (opcional)</label>
          <div className="voice-recorder">
            <button
              type="button"
              id="recordBtn"
              className={`voice-btn ${isRecording ? 'recording' : ''}`}
              onClick={handleVoiceRecord}
              style={{ background: isRecording ? '#dc3545' : '#6c757d' }}
            >
              <svg viewBox="0 0 24 24">
                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85C16.52 14.2 14.47 16 12 16s-4.52-1.8-4.93-4.15c-.08-.49-.49-.85-.98-.85s-.9.36-.98.85C6.52 17.2 8.47 19 12 19s4.52-1.8 4.93-4.15c.08-.49.49-.85.98-.85zM12 19c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
              </svg>
            </button>
            <div className="voice-status" id="voiceStatus">{voiceStatus}</div>

            {/* New recording preview */}
            {audioBlob && (
              <div className="voice-player" id="voicePlayer">
                <audio id="audioPreview" controls src={URL.createObjectURL(audioBlob)} />
                <div className="voice-actions">
                  <button type="button" className="btn btn-danger" onClick={deleteVoice}>Excluir</button>
                </div>
              </div>
            )}

            {/* Existing voice */}
            {!audioBlob && voiceNote && (
              <div className="voice-player" id="existingVoice">
                <audio controls src={`/storage/voice/${voiceNote}`} />
                <div className="voice-actions">
                  <button type="button" className="btn btn-danger" onClick={deleteVoice}>Excluir gravação existente</button>
                </div>
              </div>
            )}
          </div>
        </div>

        <div style={{ display: 'flex', gap: '1rem' }}>
          <button type="submit" className="btn" disabled={saving}>
            {saving ? 'Salvando...' : 'Salvar'}
          </button>
          <Link to="/notes" className="btn" style={{ background: '#666' }}>Cancelar</Link>
        </div>
      </form>
    </div>
  );
}

export default NoteForm;

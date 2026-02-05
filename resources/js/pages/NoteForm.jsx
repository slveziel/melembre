import React, { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

function NoteForm() {
    const { id } = useParams();
    const isEdit = Boolean(id);
    const navigate = useNavigate();

    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [saving, setSaving] = useState(false);

    // Voice recording
    const [isRecording, setIsRecording] = useState(false);
    const [voiceUrl, setVoiceUrl] = useState(null);
    const [recordingStatus, setRecordingStatus] = useState('');
    const mediaRecorderRef = useRef(null);
    const chunksRef = useRef([]);

    useEffect(() => {
        if (isEdit) {
            fetchNote();
        }
    }, [id]);

    const fetchNote = async () => {
        setLoading(true);
        try {
            const res = await fetch(`/api/notes/${id}`, { credentials: 'include' });
            if (!res.ok) throw new Error('Erro ao carregar anotação');
            const data = await res.json();
            setTitle(data.title);
            setContent(data.content || '');
            if (data.voice_note) {
                setVoiceUrl(`/storage/voice/${data.voice_note}`);
            }
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const startRecording = async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorderRef.current = new MediaRecorder(stream);
            chunksRef.current = [];

            mediaRecorderRef.current.ondataavailable = (e) => {
                if (e.data.size > 0) {
                    chunksRef.current.push(e.data);
                }
            };

            mediaRecorderRef.current.onstop = () => {
                const blob = new Blob(chunksRef.current, { type: 'audio/webm' });
                const url = URL.createObjectURL(blob);
                setVoiceUrl(url);
                setRecordingStatus('Gravação concluída!');
                setTimeout(() => setRecordingStatus(''), 2000);
            };

            mediaRecorderRef.current.start();
            setIsRecording(true);
            setRecordingStatus('Gravando...');
        } catch (err) {
            setRecordingStatus('Erro ao acessar microfone');
        }
    };

    const stopRecording = () => {
        if (mediaRecorderRef.current && isRecording) {
            mediaRecorderRef.current.stop();
            setIsRecording(false);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setSaving(true);

        try {
            const method = isEdit ? 'PUT' : 'POST';
            const url = isEdit ? `/api/notes/${id}` : '/api/notes';

            const res = await fetch(url, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, content }),
                credentials: 'include'
            });

            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || 'Erro ao salvar');
            }

            // Upload voice if recorded
            if (voiceUrl && !voiceUrl.startsWith('/storage/')) {
                const voiceRes = await fetch(`/api/notes/${data.id}/voice`, {
                    method: 'POST',
                    credentials: 'include',
                    body: createVoiceFormData(voiceUrl)
                });
                if (!voiceRes.ok) {
                    console.error('Erro ao fazer upload do áudio');
                }
            }

            navigate('/');
        } catch (err) {
            setError(err.message);
        } finally {
            setSaving(false);
        }
    };

    const createVoiceFormData = (blobUrl) => {
        return new Promise((resolve) => {
            fetch(blobUrl)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('voice', blob, 'voice.webm');
                    resolve(formData);
                });
        });
    };

    if (loading) return <div className="card">Carregando...</div>;

    return (
        <div className="card">
            <h1 className="page-title">{isEdit ? 'Editar Anotação' : 'Nova Anotação'}</h1>

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

                <div className="form-group">
                    <label>Nota de voz (opcional)</label>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                        {!isRecording ? (
                            <button
                                type="button"
                                onClick={startRecording}
                                className="btn"
                                style={{ background: '#6c757d' }}
                            >
                                🎤 Gravar
                            </button>
                        ) : (
                            <button
                                type="button"
                                onClick={stopRecording}
                                className="btn"
                                style={{ background: '#dc3545' }}
                            >
                                ⏹️ Parar
                            </button>
                        )}
                        {recordingStatus && (
                            <span style={{ color: recordingStatus.includes('Erro') ? '#dc3545' : '#666' }}>
                                {recordingStatus}
                            </span>
                        )}
                    </div>
                    {voiceUrl && (
                        <div style={{ marginTop: '0.5rem' }}>
                            <audio controls src={voiceUrl} />
                        </div>
                    )}
                </div>

                <div style={{ display: 'flex', gap: '1rem', marginTop: '1rem' }}>
                    <button type="submit" className="btn" disabled={saving}>
                        {saving ? 'Salvando...' : 'Salvar'}
                    </button>
                    <button
                        type="button"
                        onClick={() => navigate('/')}
                        className="btn"
                        style={{ background: '#666' }}
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    );
}

export default NoteForm;

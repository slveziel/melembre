import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

function Notes() {
    const [notes, setNotes] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        fetchNotes();
    }, []);

    const fetchNotes = async () => {
        try {
            const res = await fetch('/api/notes', { credentials: 'include' });
            if (!res.ok) throw new Error('Erro ao carregar anotações');
            const data = await res.json();
            setNotes(data.data || []);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Excluir esta anotação?')) return;

        try {
            const res = await fetch(`/api/notes/${id}`, {
                method: 'DELETE',
                credentials: 'include'
            });
            if (!res.ok) throw new Error('Erro ao excluir');
            setNotes(notes.filter(n => n.id !== id));
        } catch (err) {
            alert(err.message);
        }
    };

    if (loading) return <div className="card">Carregando...</div>;
    if (error) return <div className="alert alert-danger">{error}</div>;

    return (
        <div>
            <div className="page-header">
                <h1 className="page-title">Minhas Anotações</h1>
                <Link to="/notes/create" className="btn">Nova Anotação</Link>
            </div>

            {notes.length > 0 ? (
                notes.map(note => (
                    <div key={note.id} className="card note">
                        <div className="note-header">
                            <div>
                                <div className="note-title">{note.title}</div>
                                <div className="note-date">
                                    {new Date(note.created_at).toLocaleString('pt-BR')}
                                </div>
                            </div>
                            <div className="note-actions">
                                <Link to={`/notes/${note.id}/edit`} className="btn">Editar</Link>
                                <button
                                    onClick={() => handleDelete(note.id)}
                                    className="btn btn-danger"
                                >
                                    Excluir
                                </button>
                            </div>
                        </div>
                        {note.content && (
                            <div className="note-content">{note.content}</div>
                        )}
                        {note.voice_note && (
                            <div className="voice-section">
                                <strong>🎙️ Nota de voz:</strong>
                                <audio
                                    controls
                                    src={`/storage/voice/${note.voice_note}`}
                                />
                            </div>
                        )}
                    </div>
                ))
            ) : (
                <div className="card empty-state">
                    <p>Nenhuma anotação ainda.</p>
                    <p style={{ marginTop: '0.5rem' }}>
                        <Link to="/notes/create">Crie sua primeira anotação</Link>
                    </p>
                </div>
            )}
        </div>
    );
}

export default Notes;

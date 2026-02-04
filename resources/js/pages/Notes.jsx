import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';

function Notes() {
  const [notes, setNotes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchNotes();
  }, []);

  const fetchNotes = async () => {
    try {
      const response = await api.get('/notes');
      setNotes(response.data.data || response.data);
    } catch (err) {
      setError('Erro ao carregar anotações');
    } finally {
      setLoading(false);
    }
  };

  const deleteNote = async (id) => {
    if (!window.confirm('Excluir esta anotação?')) return;

    try {
      await api.delete(`/notes/${id}`);
      setNotes(notes.filter(n => n.id !== id));
    } catch (err) {
      alert('Erro ao excluir anotação');
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  if (loading) {
    return <div className="loading">Carregando...</div>;
  }

  return (
    <div>
      <div className="notes-header">
        <h1>Minhas Anotações</h1>
        <Link to="/notes/new" className="btn">Nova Anotação</Link>
      </div>

      {error && <div className="alert alert-danger">{error}</div>}

      {notes.length > 0 ? (
        <div className="notes-list">
          {notes.map(note => (
            <div key={note.id} className="card note">
              <div className="note-header">
                <div>
                  <div className="note-title">{note.title}</div>
                  <div className="note-date">{formatDate(note.created_at)}</div>
                </div>
                <div className="actions">
                  <Link to={`/notes/${note.id}/edit`} className="btn">Editar</Link>
                  <button onClick={() => deleteNote(note.id)} className="btn btn-danger">Excluir</button>
                </div>
              </div>
              {note.content && (
                <div className="note-content">{note.content}</div>
              )}
              {note.voice_note && (
                <div style={{ marginTop: '1rem', borderTop: '1px solid #eee', paddingTop: '1rem' }}>
                  <strong>🎙️ Nota de voz:</strong>
                  <audio controls src={`/storage/voice/${note.voice_note}`} style={{ width: '100%', marginTop: '0.5rem' }} />
                </div>
              )}
            </div>
          ))}
        </div>
      ) : (
        <div className="card notes-empty">
          <p>Nenhuma anotação ainda.</p>
          <p><Link to="/notes/new" className="link">Crie sua primeira anotação</Link></p>
        </div>
      )}
    </div>
  );
}

export default Notes;

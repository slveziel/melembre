import { useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';

function ForgotPassword() {
  const [email, setEmail] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    try {
      await api.post('/forgot-password', { email });
      setSuccess('Link de redefinição enviado para seu email!');
    } catch (err) {
      setError(err.response?.data?.message || 'Erro ao enviar link');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-box card">
      <h1>Esqueci minha senha</h1>
      <p style={{ marginBottom: '1rem', color: '#666' }}>
        Digite seu email e enviaremos um link para redefinir sua senha.
      </p>

      {success && <div className="alert alert-success">{success}</div>}
      {error && <div className="alert alert-danger">{error}</div>}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="email">Email</label>
          <input
            type="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            autoFocus
          />
        </div>

        <button type="submit" className="btn" style={{ width: '100%' }} disabled={loading}>
          {loading ? 'Enviando...' : 'Enviar link de redefinição'}
        </button>
      </form>

      <p style={{ marginTop: '1rem', textAlign: 'center' }}>
        <Link to="/login" className="link">Voltar ao login</Link>
      </p>
    </div>
  );
}

export default ForgotPassword;

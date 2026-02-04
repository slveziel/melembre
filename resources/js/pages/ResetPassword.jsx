import { useState } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import api from '../services/api';

function ResetPassword() {
  const { token } = useParams();
  const [email, setEmail] = useState(new URLSearchParams(window.location.search).get('email') || '');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (password !== passwordConfirmation) {
      setError('As senhas não coincidem');
      return;
    }

    setLoading(true);

    try {
      await api.post('/reset-password', {
        token,
        email,
        password,
        password_confirmation: passwordConfirmation
      });
      setSuccess('Senha redefinida com sucesso!');
      setTimeout(() => navigate('/login'), 2000);
    } catch (err) {
      const errors = err.response?.data?.errors;
      if (errors) {
        const firstError = Object.values(errors)[0][0];
        setError(firstError);
      } else {
        setError(err.response?.data?.message || 'Erro ao redefinir senha');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-box card">
      <h1>Redefinir senha</h1>

      {success && <div className="alert alert-success">{success}</div>}
      {error && <div className="alert alert-danger">{error}</div>}

      <form onSubmit={handleSubmit}>
        <input type="hidden" name="token" value={token} />

        <div className="form-group">
          <label htmlFor="email">Email</label>
          <input
            type="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            readOnly={!!email}
            style={{ background: email ? '#f5f5f5' : undefined }}
          />
        </div>

        <div className="form-group">
          <label htmlFor="password">Nova senha</label>
          <input
            type="password"
            id="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            minLength={6}
          />
        </div>

        <div className="form-group">
          <label htmlFor="password_confirmation">Confirmar nova senha</label>
          <input
            type="password"
            id="password_confirmation"
            value={passwordConfirmation}
            onChange={(e) => setPasswordConfirmation(e.target.value)}
            required
          />
        </div>

        <button type="submit" className="btn" style={{ width: '100%' }} disabled={loading}>
          {loading ? 'Redefinindo...' : 'Redefinir senha'}
        </button>
      </form>
    </div>
  );
}

export default ResetPassword;

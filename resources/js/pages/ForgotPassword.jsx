import React, { useState } from 'react';
import { Link } from 'react-router-dom';

function ForgotPassword() {
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setMessage('');
        setLoading(true);

        try {
            const res = await fetch('/api/forgot-password', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Erro ao enviar email');
            setMessage(data.message || 'Link de redefinição enviado para seu email!');
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="auth-container">
            <div className="card">
                <h1 className="auth-title">Esqueci minha senha</h1>
                <p style={{ marginBottom: '1rem', color: '#666' }}>
                    Digite seu email e enviaremos um link para redefinir sua senha.
                </p>

                {message && <div className="alert alert-success">{message}</div>}
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
                        />
                    </div>

                    <button type="submit" className="btn btn-block" disabled={loading}>
                        {loading ? 'Enviando...' : 'Enviar link de redefinição'}
                    </button>
                </form>

                <div className="auth-links">
                    <p><Link to="/login">Voltar ao login</Link></p>
                </div>
            </div>
        </div>
    );
}

export default ForgotPassword;

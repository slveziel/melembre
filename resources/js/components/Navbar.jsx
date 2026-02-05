import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

function Navbar() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = async () => {
        await logout();
        navigate('/login');
    };

    return (
        <nav className="navbar">
            <Link to="/" className="navbar-brand">melembre 🔥</Link>
            <div className="navbar-links">
                {user ? (
                    <>
                        <span className="nav-user">{user.name}</span>
                        <button onClick={handleLogout} className="btn" style={{ padding: '0.3rem 0.8rem' }}>
                            Sair
                        </button>
                    </>
                ) : (
                    <>
                        <Link to="/login" className="nav-link">Entrar</Link>
                        <Link to="/register" className="nav-link">Cadastrar</Link>
                    </>
                )}
            </div>
        </nav>
    );
}

export default Navbar;

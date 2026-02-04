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
    <nav>
      <Link to={user ? "/notes" : "/login"} className="brand">melembre 🔥</Link>
      {user && (
        <div>
          <span>{user.name}</span>
          <button
            onClick={handleLogout}
            style={{ background: 'none', border: 'none', color: 'white', cursor: 'pointer', marginLeft: '1rem' }}
          >
            Sair
          </button>
        </div>
      )}
    </nav>
  );
}

export default Navbar;

import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './context/AuthContext';
import Login from './pages/Login';
import Register from './pages/Register';
import ForgotPassword from './pages/ForgotPassword';
import ResetPassword from './pages/ResetPassword';
import Notes from './pages/Notes';
import NoteForm from './pages/NoteForm';
import Navbar from './components/Navbar';

function PrivateRoute({ children }) {
  const { user, loading } = useAuth();

  if (loading) {
    return <div className="loading">Carregando...</div>;
  }

  return user ? children : <Navigate to="/login" />;
}

function App() {
  return (
    <div className="app">
      <Navbar />
      <main className="container">
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/forgot-password" element={<ForgotPassword />} />
          <Route path="/reset-password/:token" element={<ResetPassword />} />
          <Route
            path="/notes"
            element={
              <PrivateRoute>
                <Notes />
              </PrivateRoute>
            }
          />
          <Route
            path="/notes/new"
            element={
              <PrivateRoute>
                <NoteForm />
              </PrivateRoute>
            }
          />
          <Route
            path="/notes/:id/edit"
            element={
              <PrivateRoute>
                <NoteForm />
              </PrivateRoute>
            }
          />
          <Route path="/" element={<Navigate to="/notes" />} />
        </Routes>
      </main>
    </div>
  );
}

export default App;

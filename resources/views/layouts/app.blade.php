<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'melembre')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; min-height: 100vh; }
        nav { background: #333; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; margin-left: 1rem; }
        nav .brand { font-weight: bold; font-size: 1.2rem; }
        .container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { background: #333; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #555; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { min-height: 150px; resize: vertical; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .note { border-left: 4px solid #333; }
        .note-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem; }
        .note-title { font-weight: bold; font-size: 1.1rem; }
        .note-date { color: #666; font-size: 0.85rem; }
        .note-content { white-space: pre-wrap; color: #444; }
        .empty { text-align: center; color: #666; padding: 2rem; }
        .actions { display: flex; gap: 0.5rem; }
        h1, h2 { margin-bottom: 1rem; }
        .auth-box { max-width: 400px; margin: 4rem auto; }
        /* Voice recording styles */
        .voice-recorder { margin: 1rem 0; }
        .voice-btn { width: 60px; height: 60px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
        .voice-btn.recording { background: #dc3545; animation: pulse 1.5s infinite; }
        .voice-btn svg { width: 24px; height: 24px; fill: white; }
        .voice-status { margin-top: 0.5rem; font-size: 0.85rem; color: #666; }
        .voice-player audio { width: 100%; margin-top: 0.5rem; }
        .voice-actions { margin-top: 0.5rem; }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            50% { transform: scale(1.1); box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('notes.index') }}" class="brand">melembre 🔥</a>
        @auth
            <div>
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; margin-left: 1rem;">Sair</button>
                </form>
            </div>
        @endauth
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>

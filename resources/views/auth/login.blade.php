@extends('app')

@section('title', 'Login - melembre')

@section('content')
<div class="auth-box card">
    <h1>Entrar</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Entrar</button>
    </form>

    <p style="margin-top: 1rem; text-align: center;">
        <a href="{{ route('password.request') }}">Esqueci minha senha</a>
    </p>
    <p style="margin-top: 0.5rem; text-align: center; color: #666;">
        Não tem conta? <a href="{{ route('register') }}">Cadastre-se</a>
    </p>
</div>
@endsection

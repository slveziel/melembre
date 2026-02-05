@extends('app')

@section('title', 'Cadastro - melembre')

@section('content')
<div class="auth-box card">
    <h1>Criar conta</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Cadastrar</button>
    </form>

    <p style="margin-top: 1rem; text-align: center;">
        Já tem conta? <a href="{{ route('login') }}">Entrar</a>
    </p>
</div>
@endsection

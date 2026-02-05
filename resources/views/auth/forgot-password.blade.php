@extends('app')

@section('title', 'Esqueci minha senha - melembre')

@section('content')
<div class="auth-box card">
    <h1>Esqueci minha senha</h1>
    <p style="margin-bottom: 1rem; color: #666;">Digite seu email e enviaremos um link para redefinir sua senha.</p>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Enviar link de redefinição</button>
    </form>

    <p style="margin-top: 1rem; text-align: center;">
        <a href="{{ route('login') }}">Voltar ao login</a>
    </p>
</div>
@endsection

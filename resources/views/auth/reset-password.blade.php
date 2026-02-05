@extends('app')

@section('title', 'Redefinir senha - melembre')

@section('content')
<div class="auth-box card">
    <h1>Redefinir senha</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly style="background: #f5f5f5;">
        </div>

        <div class="form-group">
            <label for="password">Nova senha</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar nova senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Redefinir senha</button>
    </form>
</div>
@endsection

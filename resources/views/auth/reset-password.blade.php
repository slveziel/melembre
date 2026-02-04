@extends('layouts.app')

@section('title', 'Redefinir senha - melembre')

@section('content')
<div class="auth-box card">
    <h1>Redefinir senha</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="{{ old('email') }}" readonly style="background: #f5f5f5;">
            @error('email')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Nova senha</label>
            <input type="password" id="password" name="password" required minlength="6">
            @error('password')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar nova senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Redefinir senha</button>
    </form>
</div>
@endsection

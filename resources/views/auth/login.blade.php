@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="d-flex justify-content-center align-items-center">
            <div class="auth-container"
                style="background: #151527; color: #FF00C8; padding: 1rem; border: 1px solid #6C1AFF; border-radius: 10px; margin-bottom: 1rem; text-align: center;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="d-flex justify-content-center align-items-center">
        <div class="auth-container">
            <h2>Login</h2>
            <form method="POST" action="/login">
                @csrf
                <input name="username" placeholder="Username" required>
                <input name="password" type="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p class="link"><a href="/register">Cadastrar Usuário</a></p>
        </div>
    </div>

@endsection

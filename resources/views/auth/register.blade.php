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
            <h2>Novo Usuário</h2>
            <form method="POST" action="/register">
                @csrf
                <input name="username" placeholder="Nome" required>
                <input type="email" name="email" placeholder="E-mail (opcional)">
                <input name="password" type="password" placeholder="Senha" required>
                <input name="password_confirmation" type="password" placeholder="Confirme a senha" required>
                <button type="submit">Cadastrar</button>
            </form>
            <p class="link"><a href="/login">Voltar ao login</a></p>
        </div>
    </div>
@endsection

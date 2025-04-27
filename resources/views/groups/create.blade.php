@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-primary" >Criar Grupo</h2>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    @endif

    <form action="{{ route('groups.store') }}" method="POST" class="mb-4">
        @csrf
        <input type="text" name="name" placeholder="Nome do grupo" required class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Criar</button>
    </form>

    <h4>Grupos Existentes</h4>
    <ul class="list-group">
        @foreach($groups as $group)
            <li class="list-group-item">{{ $group->name }}</li>
        @endforeach
    </ul>
</div>
@endsection

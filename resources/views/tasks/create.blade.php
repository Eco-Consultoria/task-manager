@extends('layouts.app')

@section('title', 'Nova Tarefa')

@section('content')
    <div class="container mt-4">
        <div class="card bg-dark text-white border-neon p-4">
            <h2 class="mb-4">Criar Nova Tarefa</h2>

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="priority" class="form-label">Prioridade</label>
                    <select name="priority" id="priority" class="form-select" required>
                        <option value="high">Alta</option>
                        <option value="medium">Média</option>
                        <option value="low">Baixa</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="group_id" class="form-label">Grupo</label>
                    <select name="group_id" id="group_id" class="form-select">
                        @foreach ($groups as $group)
                            @if ($group->active == 1)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="assign_to_me" id="assign_to_me">
                    <label class="form-check-label" for="assign_to_me">
                        Atribuir a mim
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Criar Tarefa</button>

            </form>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-light btn-sm neon-btn border-neon mt-3">Voltar</a>
        </div>
    </div>
@endsection

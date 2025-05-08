@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
    <div class="container mt-4">
        <div class="card bg-dark text-white border-neon p-4">
            <h2 class="text-primary mb-4">Editar Tarefa</h2>

            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $task->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $task->description) }}</textarea>
                </div>

                @if ($task->note)
                    <div class="mb-3">
                        @if (auth()->user()->is_manager)
                            <label for="note" class="form-label">Observações</label>
                            <textarea name="note" id="note" class="form-control" rows="4">{{ old('note', $task->note) }}</textarea>
                        @else
                            <p><strong>Observações:</strong> {{ $task->note }}</p>
                        @endif
                    </div>
                @endif


                <div class="mb-3">
                    <label for="task_solution" class="form-label">Descrição da Resolução da Tarefa</label>
                    <textarea name="task_solution" id="task_solution" class="form-control" rows="4">{{ old('task_solution', $task->task_solution) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="priority" class="form-label">Prioridade</label>
                    <select name="priority" id="priority" class="form-select">
                        <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Média</option>
                        <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Baixa</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        @foreach ($statuses as $key => $label)
                            @if ($key != 'approved' && $key != 'in_progress_returned')
                                <option value="{{ $key }}" {{ $task->status == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="group_id" class="form-label">Grupo</label>
                    <select name="group_id" id="group_id" class="form-select">
                        <option value="">Sem grupo</option>
                        @foreach ($groups as $group)
                            @if ($group->active == 1 || $task->group_id == $group->id)
                                <option value="{{ $group->id }}" {{ $task->group_id == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    @if (auth()->user()->is_manager)
                        <label for="users" class="form-label">Responsáveis</label>
                        <select name="users[]" id="users" class="form-select" multiple>
                            @foreach ($users as $user)
                                @if ($user->active == 1 || $task->users->contains($user->id))
                                    <option value="{{ $user->id }}"
                                        {{ $task->users->contains($user->id) ? 'selected' : '' }}>
                                        {{ $user->username }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Segure Ctrl (ou Cmd no Mac) para selecionar múltiplos
                            usuários.</small>
                    @else
                        <p>Responsável(eis):
                            @foreach ($task->users as $user)
                                <span class="badge bg-info">{{ $user->username }}</span>
                            @endforeach
                        </p>

                    @endif

                </div>

                <button class="btn btn-primary btn-sm w-100 neon-btn border-neon">Salvar Alterações</button>
            </form>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-light btn-sm neon-btn border-neon mt-3">Voltar</a>
        </div>
    </div>
@endsection

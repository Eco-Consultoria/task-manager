@extends('layouts.app')

@section('content')
    <div class="mt-4 text-white">

        <div class="row position-relative align-items-center mb-3">
            <div class="col text-center">
                <h2 class="text-primary mb-4">Tarefas</h2>
            </div>
            <div class="col-auto position-absolute end-0">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm neon-btn border-neon neon-link">+ Nova Tarefa</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control bg-dark text-white border-neon"
                        placeholder="Buscar por título, descrição..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="priority" class="form-select bg-dark text-white border-neon">
                        <option value="">Todas as Prioridades</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Média</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Baixa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="group_id" class="form-select bg-dark text-white border-neon">
                        <option value="">Todos os Grupos</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="user_id" class="form-select bg-dark text-white border-neon">
                        <option value="">Todos os Usuários</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary neon-btn border-neon">Filtrar</button>
                </div>
            </div>
        </form>

        @if ($tasks->isEmpty())
            <div class="alert alert-warning text-center">
                Nenhuma tarefa cadastrada até o momento.
            </div>
        @else
            <div class="container-fluid px-3">
                <div class="d-flex flex-nowrap" style="gap: 1rem; overflow-x: auto;">
                    @foreach ($statuses as $statusKey => $statusLabel)
                        <div class="card bg-dark text-white border-neon flex-shrink-0" style="min-width: 250;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>{{ $statusLabel }}</strong>
                                <span class="badge bg-info">{{ $tasks->where('status', $statusKey)->count() }}</span>
                            </div>

                            <div class="card-body p-0">
                                @php
                                    $filtered = $tasks->where('status', $statusKey);
                                @endphp

                                @if ($filtered->isEmpty())
                                    <div class="text-center text-muted">Nenhuma tarefa nesta categoria.</div>
                                @else
                                    <div class="kanban-column p-1">
                                        @foreach ($filtered as $task)
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="text-decoration-none text-white">
                                                <div
                                                    class="kanban-card {{ $task->updated_at > $lastViewedAt ? 'new-data' : '' }} {{ $task->priority }}-priority">
                                                    <h6 class="fw-bold">{{ $task->title }}</h6>
                                                    <p class="mb-1 small text-white">
                                                        {{ Str::limit($task->description, 80) }}</p>
                                                    <div class="mt-2 mb-1">
                                                        @foreach ($task->users as $user)
                                                            <span class="badge bg-secondary">{{ $user->username }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span
                                                            class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                                            {{ $task->priority == 'high' ? 'Prioridade Alta' : ($task->priority == 'medium' ? 'Prioridade Normal' : 'Prioridade Baixa') }}
                                                        </span>
                                                        <span class="text-info small">Grupo:
                                                            {{ $task->group->name ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection

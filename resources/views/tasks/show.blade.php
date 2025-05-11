@extends('layouts.app')

@section('title', 'Detalhes da Tarefa')

@section('content')
    <div class="container mt-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        <div class="card bg-dark text-white border-neon p-4">
            <h2 class="text-primary mb-3">{{ $task->title }}</h2>

            <div class="row">
                <div class="col">
                    <p><strong>Criada por:</strong> {{ $task->creator->username ?? 'N/A' }}</p>
                </div>
                @if ($task->status == 'cancelled')
                    <p><strong>Cancelada por:</strong> {{ $task->canceller->username ?? 'N/A' }}, <strong>em:</strong>
                        {{ $task->cancelled_at->format('d/m/Y H:i') ?? 'N/A' }}</p>
                @elseif ($task->status == 'approved')
                    <p><strong>Aprovada por:</strong> {{ $task->approver->username ?? 'N/A' }}, <strong>em:</strong>
                        {{ $task->approved_at->format('d/m/Y H:i') ?? 'N/A' }}</p>
                @endif
                <div class="col">
                    <p><strong>Responsáveis:</strong>
                        @foreach ($task->users as $user)
                            <span class="badge bg-secondary me-1">{{ $user->username }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>
                        <strong>Status: </strong>
                        <span class="badge bg-info">{{ ucfirst($statuses[$task->status]) }}</span>
                    </p>
                </div>
                <div class="col">
                    <p>
                        <strong>Prioridade:</strong>
                        <span
                            class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">{{ $task->priority === 'high' ? 'Alta' : ($task->priority === 'medium' ? 'Média' : 'Baixa') }}</span>
                    </p>
                </div>
                <div class="col">
                    <p><strong>Grupo:</strong> {{ $task->group->name ?? 'Não atribuído' }}</p>
                </div>
            </div>
            <div>
                <p><strong>Descrição:</strong> {{ $task->description }}</p>

                @if ($task->note)
                    <p><strong>Observação:</strong> {{ $task->note }}</p>
                @endif

                @if ($task->task_solution)
                    <p><strong>Resolução da Tarefa:</strong> {{ $task->task_solution }}</p>
                @endif
            </div>

            @if (
                ($task->created_by == auth()->user()->id || auth()->user()->is_manager || $task->users->contains(auth()->user())) &&
                    $task->status != 'approved' &&
                    $task->status != 'completed')
                <a href="{{ route('tasks.edit', $task->id) }}"
                    class="btn btn-primary btn-sm w-100 neon-btn border-neon neon-link mt-3">Editar</a>
            @elseif ($task->status == 'completed' && auth()->user()->is_manager)
                <a href="{{ route('tasks.showReview', $task->id) }}"
                    class="btn btn-primary btn-sm w-100 neon-btn border-neon neon-link mt-3">Aprovar</a>
            @endif

            <a href="{{ route('tasks.index') }}"
                class="btn btn-outline-light btn-sm w-100 neon-btn border-neon mt-3">Voltar</a>
        </div>
    </div>
@endsection

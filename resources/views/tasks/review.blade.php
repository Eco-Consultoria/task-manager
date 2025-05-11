@extends('layouts.app')

@section('title', 'Revisar Tarefa')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4 text-info">Revisar Tarefa</h2>

        <div class="card bg-dark text-white border border-info">
            <div class="card-body">
                <h4 class="text-primary">{{ $task->title }}</h4>
                <p><strong>Descrição:</strong> {{ $task->description }}</p>
                <p><strong>Solução da tarefa:</strong> {{ $task->task_solution ?? 'Não informado' }}</p>
                <p><strong>Status atual:</strong> {{ ucfirst($statuses[$task->status]) }}</p>

                <form action="{{ route('tasks.review', $task->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="status" class="form-label">Aprovar / Devolver</label>
                        <select name="status" id="status" class="form-select">
                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Aprovar</option>
                            <option value="in_progress_returned"
                                {{ old('status') == 'in_progress_returned' ? 'selected' : '' }}>Devolver para andamento
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Observação (em caso de devolução)</label>
                        <textarea name="note" id="note" class="form-control" rows="4">{{ old('note', $task->note) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 neon-btn border-neon">Salvar</button>

                </form>
                <a href="{{ route('tasks.index') }}"
                    class="btn btn-outline-light btn-sm w-100 neon-btn border-neon mt-3">Voltar</a>
            </div>
        </div>
    </div>
@endsection

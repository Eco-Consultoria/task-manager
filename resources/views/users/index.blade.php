@extends('layouts.app')

{{-- @section('content')
    <div class="container mt-5">
        <h2 class="text-primary mb-4">Gerenciar Usuários</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Grupos</th>
                    <th>Gerente?</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td class="{{ $user->updated_at > $lastViewedAt ? 'new-data' : '' }}">{{ $user->username }}</td>
                            <td>
                                <select name="groups[]" class="form-select" multiple size="3">
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ $user->group->contains($group->id) ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="is_manager" value="1"
                                    {{ $user->is_manager ? 'checked' : '' }}>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">Salvar</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection --}}

@section('content')
    <div class="container mt-5">
        <h2 class="text-primary mb-4">Gerenciar Usuários</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Grupos</th>
                    <th>Gerente?</th>
                    <th>Ações</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td class="{{ $user->updated_at > $lastViewedAt ? 'new-data' : '' }}">
                                {{ $user->username }}
                            </td>
                            <td>
                                <select name="groups[]" class="form-select" multiple size="3">
                                    @foreach ($groups as $group)
                                    @if ($group->active == 1 || $user->group->contains($group->id))
                                        <option value="{{ $group->id }}"
                                            {{ $user->group->contains($group->id) ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="is_manager" value="1"
                                    {{ $user->is_manager ? 'checked' : '' }}>
                            </td>
                            <td class="">
                                <button class="btn btn-sm btn-primary">Salvar</button>
                            </td>
                        </form>

                        <!-- Botão que abre o modal -->
                        <td class="">
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $user->id }}">
                                Excluir
                            </button>

                            <!-- Modal de confirmação -->
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">
                                                Confirmar exclusão
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir o usuário
                                            <strong>{{ $user->username }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="width: 100%;">
                                                @csrf
                                                @method('DELETE')
                                                <div class="row justify-content-center gx-3">
                                                    <div class="col-6">
                                                        <button type="submit" class="btn btn-danger w-100">Sim, excluir</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

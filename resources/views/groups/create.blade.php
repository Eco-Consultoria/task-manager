@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-primary">Criar Grupo</h2>
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

        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th> <h4>Grupos Existentes</h4></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $group->id }}">
                                Excluir
                            </button>

                            <!-- Modal de confirmação -->
                            <div class="modal fade" id="deleteModal{{ $group->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel{{ $group->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $group->id }}">
                                                Confirmar exclusão
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir o grupo
                                            <strong>{{ $group->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="width: 100%;">
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

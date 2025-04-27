@extends('layouts.app')

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
@endsection

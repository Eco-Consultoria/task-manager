@php
    $notifications = \App\Models\Notification::where('user_id', auth()->id())
        ->where('seen', false)
        ->get();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top px-3">
    <a class="navbar-brand" href="{{ route('dashboard') }}">TaskManager</a>

    <!-- Botão hamburguer -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
        aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Itens da navbar -->
    <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tasks.index') }}">Tarefas</a>
            </li>


            @if (auth()->user()->is_manager)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('groups.create') }}">Grupos</a>
                </li>
            @endif

            <li class="nav-item">
                <span>Bem vindo {{ auth()->user()->username }}</span>
            </li>

            <li class="nav-item position-relative">
                <a class="nav-link" href="#" onclick="toggleNotifications(event)">
                    🔔
                    @if ($notifications->count() > 0)
                        <span id="notification-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $notifications->count() }}
                        </span>
                    @endif
                </a>

                <!-- Dropdown de Notificações -->
                <div id="notificationDropdown" class="dropdown-menu p-2 shadow-sm"
                    style="display: none; position: absolute; right: 0; top: 100%; z-index: 999;">
                    @if ($notifications->isEmpty())
                        <small class="text-muted">Sem novas notificações</small>
                    @else
                        @foreach ($notifications as $notification)
                            <div class="dropdown-item small text-white">
                                {{ $notification->message }}
                                <br>
                                <small class="text-white">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <hr class="my-1">
                        @endforeach
                    @endif
                </div>
            </li>

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="GET" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary neon-btn border-neon m-auto">Sair</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<script>
    function toggleNotifications(event) {
        event.preventDefault();

        const dropdown = document.getElementById('notificationDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

        // Marcar como lidas
        fetch("{{ route('notifications.markAsRead') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        }).then(response => {
            if (response.ok) {
                document.getElementById('notification-badge')?.remove();
            }
        });
    }

    // Fechar dropdown se clicar fora
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown && !dropdown.contains(e.target) && !e.target.closest('a.nav-link')) {
            dropdown.style.display = 'none';
        }
    });
</script>

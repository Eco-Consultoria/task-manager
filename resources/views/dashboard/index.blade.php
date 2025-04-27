@extends('layouts.app')

@section('content')
    <div class="container mt-4 text-white">
        <h2 class="text-primary mb-4">Painel de Controle</h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body text-center">
                        <h5 class="card-title">Tarefas Totais</h5>
                        <p class="card-text fs-4">{{ $totalTasks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body text-center">
                        <h5 class="card-title">Não Iniciadas</h5>
                        <p class="card-text fs-4">{{ $notStartedTasks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body text-center">
                        <h5 class="card-title">Em Andamento</h5>
                        <p class="card-text fs-4">{{ $inProgressTasks }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body text-center">
                        <h5 class="card-title">Aguardando Aprovação</h5>
                        <p class="card-text fs-4">{{ $awaitingApprovalTasks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body text-center">
                        <h5 class="card-title">Finalizadas</h5>
                        <p class="card-text fs-4">{{ $completedTasks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body text-center">
                        <h5 class="card-title">Canceladas</h5>
                        <p class="card-text fs-4">{{ $cancelledTasks }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body">
                        <h5 class="card-title">Tarefas por Status</h5>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card bg-dark text-white border-neon">
                    <div class="card-body">
                        <h5 class="card-title">Tarefas por Prioridade</h5>
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const statusData = @json($tasksByStatus);
        const priorityData = @json($tasksByPriority);

        const statusLabels = {
            'not_started': 'Não iniciada',
            'in_progress': 'Em andamento',
            'in_progress_returned': 'Em andamento (devolvida)',
            'on_hold': 'Suspensa',
            'cancelled': 'Cancelada',
            'completed': 'Finalizada',
            'approved': 'Aprovada'
        };

        const priorityLabels = {
            'high': 'Alta',
            'medium': 'Média',
            'low': 'Baixa'
        };

        const statusLabelsTranslated = Object.keys(statusData).map(key => statusLabels[key] || key);
        const priorityLabelsTranslated = Object.keys(priorityData).map(key => priorityLabels[key] || key);

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabelsTranslated,
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: ['#00f0ff', '#ff00f7', '#ff7300', '#00ff88', '#ff0', '#ff0033',
                        '#5b00ff'
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('priorityChart'), {
            type: 'bar',
            data: {
                labels: priorityLabelsTranslated,
                datasets: [{
                    label: 'Quantidade',
                    data: Object.values(priorityData),
                    backgroundColor: ['#e60000', '#ffc107', '#2eb82e']
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            color: '#fff'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#fff'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                }
            }
        });
    </script>
@endsection

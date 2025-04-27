<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'approved')->count();
        $awaitingApprovalTasks = Task::where('status', 'completed')->count();
        $inProgressTasks = Task::where('status', 'in_progress')->orWhere('status', 'in_progress_returned')->orWhere('status', 'on_hold')->count();
        $notStartedTasks = Task::where('status', 'not_started')->count();
        $cancelledTasks = Task::where('status', 'cancelled')->count();


        $tasksByStatus = Task::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $tasksByPriority = Task::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority');

        return view('dashboard.index', compact(
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'awaitingApprovalTasks',
            'notStartedTasks',
            'cancelledTasks',
            'tasksByStatus',
            'tasksByPriority'
        ));
    }
}

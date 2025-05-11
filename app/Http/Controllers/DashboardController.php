<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->is_manager) {
            $groupIds = Group::select('id')->get()->toArray();
        } else {
            $groupIds = $user->group->pluck('id');
        }

        $totalTasks = Task::whereIn('group_id', $groupIds)->count();
        $completedTasks = Task::whereIn('group_id', $groupIds)->where('status', 'approved')->count();
        $awaitingApprovalTasks = Task::whereIn('group_id', $groupIds)->where('status', 'completed')->count();
        $inProgressTasks = Task::whereIn('group_id', $groupIds)->where('status', 'in_progress')->orWhere('status', 'in_progress_returned')->orWhere('status', 'on_hold')->count();
        $notStartedTasks = Task::whereIn('group_id', $groupIds)->where('status', 'not_started')->count();
        $cancelledTasks = Task::whereIn('group_id', $groupIds)->where('status', 'cancelled')->count();


        $tasksByStatus = Task::select('status', DB::raw('count(*) as total'))
            ->whereIn('group_id', $groupIds)
            ->groupBy('status')
            ->pluck('total', 'status');

        $tasksByPriority = Task::select('priority', DB::raw('count(*) as total'))
            ->whereIn('group_id', $groupIds)
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

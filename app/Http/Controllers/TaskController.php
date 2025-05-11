<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Task;
use App\Models\User;
use App\Models\Group;
use App\Models\ScreenView;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public $statuses = [
        'not_started' => 'Não iniciada',
        'in_progress' => 'Em andamento',
        'in_progress_returned' => 'Em andamento (devolvida)',
        'completed' => 'Finalizada',
        'approved' => 'Aprovada',
        'on_hold' => 'Suspensa',
        'cancelled' => 'Cancelada',
    ];

    public function index(Request $request)
    {
        $statuses = $this->statuses;
        $priorities = ['high', 'medium', 'low'];   
        $user = auth()->user();

        $query = Task::with(['group', 'users']);

        if (!$user->is_manager) {
            $groupIds = $user->group->pluck('id');
            $query->whereIn('group_id', $groupIds);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->filled('user_id')) {
            $query->whereHas('users', fn($q) => $q->where('user_id', $request->user_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        $tasks = $query->get();
        $users = User::orderBy('username')->get();
        $groups = Group::orderBy('name')->get();


        $screen = 'tasks.index';
        $screenView = ScreenView::firstOrCreate(
            ['user_id' => $user->id, 'screen' => $screen],
            ['last_viewed_at' => now()]
        );
        $lastViewedAt = $screenView->last_viewed_at;

        $screenView->update(['last_viewed_at' => now()]);


        return view('tasks.index', compact('tasks', 'users', 'groups', 'statuses', 'priorities', 'lastViewedAt'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->status = $request->input('status');
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Status atualizado!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:high,medium,low',
            'group_id' => 'required|exists:groups,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'group_id' => $request->group_id,
            'status' => 'not_started',
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->has('assign_to_me')) {
            $task->users()->attach(auth()->id());
        }

        $message = 'A tarefa ' . $task->title . ' foi criada por ' . auth()->user()->username . '.';
        foreach ($task->users as $user) {
            NotificationHelper::notifyUser($user, $message, $task->id);
        }

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso.');
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_solution' => 'nullable|string',
            'note' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'group_id' => 'required|exists:groups,id',
            'status' => 'required|in:not_started,in_progress,in_progress_returned,on_hold,cancelled,completed,approved',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'task_solution' => $request->task_solution,
            'priority' => $request->priority,
            'group_id' => $request->group_id,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        if ($request->status === 'completed') {
            $task->completed_by = auth()->id();
            $task->completed_at = now();
        }

        if ($request->status === 'in_progress') {
            $task->started_at = now();
        }

        if ($request->status === 'cancelled') {
            $task->cancelled_at = now();
            $task->cancelled_by = auth()->id();
        }

        if ($request->status === 'approved') {
            $task->approved_at = now();
            $task->approved_by = auth()->id();
        }

        $task->users()->sync($request->input('users', []));

        $task->save();

        $message = 'A tarefa ' . $task->title . ' foi atualizada por ' . auth()->user()->username . '.';
        foreach ($task->users as $user) {
            NotificationHelper::notifyUser($user, $message, $task->id);
        }

        if ($request->status === 'completed') {
            $managers = User::where('is_manager', 1)->get();
            $message = 'A tarefa ' . $task->title . ' foi finalizada por ' . auth()->user()->username . ' e está aguardando aprovação.';
            foreach ($managers as $manager) {
                NotificationHelper::notifyUser($manager, $message, $task->id);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso.');
    }

    public function destroy(Task $task)
    {
        if (auth()->user()->is_manager || $task->created_by === auth()->id()) {
            $task->users()->detach();
            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Tarefa removida com sucesso.');
        }

        abort(403, 'Você não tem permissão para excluir esta tarefa.');
    }

    public function create()
    {
        $groups = \App\Models\Group::all();
        return view('tasks.create', compact('groups'));
    }

    public function show(Task $task)
    {
        $statuses = $this->statuses;
        $task->load(['users', 'group', 'creator']);

        return view('tasks.show', compact('task', 'statuses'));
    }

    public function edit(Task $task)
    {
        $groups = Group::all();
        if (auth()->user()->is_manager) {
            $users = User::orderBy('username')->get();
        } else {
            $users = [];
        }

        $statuses = $this->statuses;

        return view('tasks.edit', compact('task', 'groups', 'statuses', 'users'));
    }

    public function showReview(Task $task)
    {
        $statuses = $this->statuses;
        return view('tasks.review', compact('task', 'statuses'));
    }

    public function review(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:in_progress_returned,approved',
            'note' => 'nullable|string',
        ]);

        $task->status = $request->status;
        $task->note = $request->note;

        if ($request->status === 'approved') {
            $task->approved_at = now();
            $task->approved_by = auth()->id();
        }

        $task->save();

        $status = $request->status === 'approved' ? 'APROVADA' : 'DEVOLVIDA';
        $message = 'A tarefa ' . $task->title . ' foi ' . $status . ' por ' . auth()->user()->username . '.';

        foreach ($task->users as $user) {
            NotificationHelper::notifyUser($user, $message, $task->id);
        }

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\ScreenView;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('group')->get();
        $groups = Group::all();

        $user = auth()->user();
        $screen = 'users.index';

        $screenView = ScreenView::firstOrCreate(
            ['user_id' => $user->id, 'screen' => $screen],
            ['last_viewed_at' => now()]
        );

        $lastViewedAt = $screenView->last_viewed_at;

        $screenView->update(['last_viewed_at' => now()]);

        return view('users.index', compact('users', 'groups', 'lastViewedAt'));
    }

    public function update(Request $request, User $user)
    {
        $user->group()->sync($request->groups ?? []);

        $user->is_manager = $request->has('is_manager');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

}


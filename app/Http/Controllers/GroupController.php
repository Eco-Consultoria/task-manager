<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create()
    {
        $groups = Group::where('active', 1)->get();
        return view('groups.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Group::create([
            'name' => $request->name
        ]);

        return redirect()->route('groups.create')->with('success', 'Grupo criado com sucesso.');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        $group->update(['active' => 0]);

        return redirect()->route('groups.create')->with('success', 'Grupo deletado com sucesso.');
    }
}

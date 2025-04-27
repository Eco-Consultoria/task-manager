<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create()
    {
        $groups = Group::all();
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
}

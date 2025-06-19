<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Auth::user()->groups()->with('users')->get();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:groups,name']);

        $group = Group::create([
            'name' => $request->name,
            'leader_id' => Auth::id(),
        ]);

        $group->users()->attach(Auth::id());

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function show(Group $group)
    {
        abort_unless($group->users->contains(Auth::id()), 403);

        $members = $group->users;
        $allUsers = User::role('student')->whereNotIn('id', $members->pluck('id'))->get();

        return view('groups.show', compact('group', 'members', 'allUsers'));
    }

    public function edit(Group $group)
    {
        if ($group->leader_id !== Auth::id()) {
            abort(403);
        }

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        if ($group->leader_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|unique:groups,name,' . $group->id,
        ]);

        $group->update(['name' => $request->name]);

        return redirect()->route('groups.index')->with('success', 'Group updated.');
    }

    public function destroy(Group $group)
    {
        if ($group->leader_id !== Auth::id()) {
            abort(403);
        }

        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted.');
    }

    public function addMember(Request $request, Group $group)
    {
        if ($group->leader_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['user_id' => 'required|exists:users,id']);

        $group->users()->syncWithoutDetaching($request->user_id);
        return back()->with('success', 'Member added.');
    }

    public function removeMember(Group $group, User $user)
    {
        if ($group->leader_id !== Auth::id()) {
            abort(403);
        }

        $group->users()->detach($user->id);
        return back()->with('success', 'Member removed.');
    }
}

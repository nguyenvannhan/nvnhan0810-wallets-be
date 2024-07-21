<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        return view('friend.index',[
            'friendList' => Friend::withCount(['borrowTransactions' => function ($query) {
                $query->where('amount', '>', 0)->orderBy('transaction_date', 'desc');
            }])->get(),
        ]);
    }

    public function show(Friend $friend)
    {
        $friend->loadMissing(['borrowTransactions' => function ($query) {
            $query->where('amount', '>', 0)->orderBy('transaction_date', 'desc');
        }]);

        return view('friend.show', [
            'friend' => $friend,
        ]);
    }

    public function create()
    {
        return view('friend.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Friend::create([
            'name' => $request->name,
        ]);

        return redirect()->route('friends.index');
    }

    public function edit(Friend $friend)
    {
        return view('friend.edit', [
            'friend' => $friend,
        ]);
    }

    public function update(Friend $friend, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $friend->name = $request->name;
        $friend->save();

        return redirect()->route('friends.index');
    }

    public function destroy(Friend $friend)
    {
        if ($friend->borrowTransactions()->count() > 0) {
            return redirect()->route('friends.index')->withErrors(['Friend has borrow transactions']);
        }

        $friend->delete();

        return redirect()->route('friends.index');
    }
}

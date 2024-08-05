<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class FollowerControler extends Controller
{
    public function follow(User $user)
    {
        $follower = auth()->user();

        $follower->followings()->attach($user);

        return back()->with('success', "followed successfully!");

    }

    public function unfollow(User $user)
    {
        $follower = auth()->user();

        $follower->followings()->detach($user);

        return back()->with('success', "unfollowed successfully!");

    }
}
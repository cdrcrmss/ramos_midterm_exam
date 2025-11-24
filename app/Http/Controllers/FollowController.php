<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow a user.
     */
    public function toggle(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot follow yourself!');
        }

        $currentUser = Auth::user();
        
        if ($currentUser->isFollowing($user)) {
            // Unfollow
            $currentUser->following()->detach($user->id);
            $message = "You unfollowed {$user->display_name}.";
        } else {
            // Follow
            $currentUser->following()->attach($user->id);
            $message = "You are now following {$user->display_name}.";
        }

        return back()->with('success', $message);
    }

    /**
     * Show followers of a user.
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->withCount(['tweets', 'followers', 'following'])
            ->paginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    /**
     * Show users that a user is following.
     */
    public function following(User $user)
    {
        $following = $user->following()
            ->withCount(['tweets', 'followers', 'following'])
            ->paginate(20);

        return view('users.following', compact('user', 'following'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tweet;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for users and messages.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $users = collect();

        if ($query) {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('bio', 'like', "%{$query}%")
                ->withCount(['tweets', 'followers', 'following'])
                ->paginate(20);
        }

        return view('search.index', compact('query', 'users'));
    }

    /**
     * Search for users only.
     */
    public function users(Request $request)
    {
        $query = $request->input('q');
        $users = collect();

        if ($query) {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('bio', 'like', "%{$query}%")
                ->withCount(['tweets', 'followers', 'following'])
                ->paginate(20);
        }

        return view('search.users', compact('query', 'users'));
    }

    /**
     * Search for tweets only.
     */
    public function tweets(Request $request)
    {
        $query = $request->input('q');
        $tweets = collect();

        if ($query) {
            $tweets = Tweet::where('content', 'like', "%{$query}%")
                ->with(['user', 'likes', 'retweets'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('search.tweets', compact('query', 'tweets'));
    }
}

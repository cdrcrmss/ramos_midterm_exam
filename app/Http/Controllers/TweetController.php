<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TweetController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tweets = Tweet::with(['user', 'likes', 'retweets'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tweets.index', compact('tweets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ]);

        Tweet::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('home')->with('success', 'Tweet posted successfully!');
    }

    public function show(Tweet $tweet)
    {
        $tweet->load(['user', 'likes', 'retweets']);
        return view('tweets.show', compact('tweet'));
    }

    public function edit(Tweet $tweet)
    {
        $this->authorize('update', $tweet);
        return view('tweets.edit', compact('tweet'));
    }

    public function update(Request $request, Tweet $tweet)
    {
        $this->authorize('update', $tweet);

        $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ]);

        $tweet->update([
            'content' => $request->input('content'),
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return redirect()->route('tweets.show', $tweet)
            ->with('success', 'Tweet updated successfully!');
    }

    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);
        
        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }

    public function likes(Tweet $tweet)
    {
        $likes = $tweet->likes()->with('user')->latest()->get();
        
        return view('tweets.likes', compact('tweet', 'likes'));
    }

    public function retweets(Tweet $tweet)
    {
        $retweets = $tweet->retweets()->with('user')->latest()->get();
        
        return view('tweets.retweets', compact('tweet', 'retweets'));
    }
}

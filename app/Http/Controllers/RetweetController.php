<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Retweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetweetController extends Controller
{
    /**
     * Toggle retweet/unretweet a tweet.
     */
    public function toggle(Request $request, Tweet $tweet)
    {
        if ($tweet->user_id === Auth::id()) {
            return back()->with('error', 'You cannot retweet your own tweet!');
        }

        $user = Auth::user();
        $existingRetweet = $user->retweets()->where('tweet_id', $tweet->id)->first();

        if ($existingRetweet) {
            // Unretweet
            $existingRetweet->delete();
            $message = 'Retweet removed successfully!';
        } else {
            // Retweet
            $user->retweets()->create([
                'tweet_id' => $tweet->id,
                'comment' => $request->input('comment'),
            ]);
            $message = 'Tweet retweeted successfully!';
        }

        return back()->with('success', $message);
    }

    /**
     * Show retweet form with optional comment.
     */
    public function create(Tweet $tweet)
    {
        if ($tweet->user_id === Auth::id()) {
            return back()->with('error', 'You cannot retweet your own tweet!');
        }

        return view('retweets.create', compact('tweet'));
    }

    /**
     * Show all retweets of a tweet.
     */
    public function show(Tweet $tweet)
    {
        $retweets = $tweet->retweets()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('retweets.show', compact('tweet', 'retweets'));
    }
}

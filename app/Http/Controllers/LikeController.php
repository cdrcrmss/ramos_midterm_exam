<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Tweet $tweet)
    {
        $user = Auth::user();
        
        $existingLike = Like::where('user_id', $user->id)
            ->where('tweet_id', $tweet->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Tweet unliked!';
        } else {
            Like::create([
                'user_id' => $user->id,
                'tweet_id' => $tweet->id,
            ]);
            $message = 'Tweet liked!';
        }

        return redirect()->back()->with('success', $message);
    }
}

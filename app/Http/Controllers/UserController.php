<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load(['tweets.likes', 'tweets.retweets']);
        
        $tweetsCount = $user->tweets()->count();
        $totalLikes = $user->tweets()->withCount('likes')->get()->sum('likes_count');
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        
        $tweets = $user->tweets()
            ->with(['likes', 'retweets'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.profile', compact('user', 'tweetsCount', 'totalLikes', 'followersCount', 'followingCount', 'tweets'));
    }

    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('users.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id, 'alpha_dash'],
            'bio' => ['nullable', 'string', 'max:500'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($user->profile_picture) {
                $oldPath = storage_path('app/public/' . $user->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        } else {
            // If no new file uploaded, keep the existing profile picture
            unset($validated['profile_picture']);
        }

        $user->update($validated);

        // Refresh the model to ensure we have the latest data
        $user->refresh();

        return redirect()->route('users.profile', $user)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the user's followers.
     */
    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);
        return view('users.followers', compact('user', 'followers'));
    }

    /**
     * Show the users that this user is following.
     */
    public function following(User $user)
    {
        $following = $user->following()->paginate(20);
        return view('users.following', compact('user', 'following'));
    }

    /**
     * Show the user's retweets.
     */
    public function retweets(User $user)
    {
        $retweets = $user->retweets()
            ->with(['tweet.user', 'tweet.likes', 'tweet.retweets'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('users.retweets', compact('user', 'retweets'));
    }
}

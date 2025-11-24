<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'is_edited',
        'edited_at'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    /**
     * Get the user that owns the tweet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all likes for the tweet.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the likes count for the tweet.
     */
    public function likesCount()
    {
        return $this->likes()->count();
    }

    /**
     * Check if the tweet is liked by a specific user.
     */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get all retweets for the tweet.
     */
    public function retweets()
    {
        return $this->hasMany(Retweet::class);
    }

    /**
     * Get the retweets count for the tweet.
     */
    public function retweetsCount()
    {
        return $this->retweets()->count();
    }

    /**
     * Check if the tweet is retweeted by a specific user.
     */
    public function isRetweetedBy(User $user)
    {
        return $this->retweets()->where('user_id', $user->id)->exists();
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
        'profile_picture',
        'last_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_active' => 'datetime',
        ];
    }

    /**
     * Get all tweets for the user.
     */
    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    /**
     * Get all likes for the user.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Check if user has liked a specific tweet.
     */
    public function hasLiked(Tweet $tweet)
    {
        return $this->likes()->where('tweet_id', $tweet->id)->exists();
    }

    /**
     * Get all retweets for the user.
     */
    public function retweets()
    {
        return $this->hasMany(Retweet::class);
    }

    /**
     * Get the users that this user is following.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Get the users that are following this user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Get messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Check if this user is following another user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Check if this user is followed by another user.
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    /**
     * Get the total likes count for all user's tweets.
     */
    public function totalLikes(): int
    {
        return $this->tweets()->withCount('likes')->get()->sum('likes_count');
    }

    /**
     * Get user's display name (username or name).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->username ?: $this->name;
    }

    /**
     * Get the user's initials for avatar display.
     */
    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Get the profile picture URL or return default.
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        if ($this->profile_picture) {
            // Generate URL with cache busting
            return asset('storage/' . $this->profile_picture) . '?v=' . $this->updated_at->timestamp;
        }
        
        // Return null to show placeholder
        return null;
    }

    /**
     * Get Gravatar URL as fallback.
     */
    public function getGravatarUrlAttribute(): string
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=150";
    }

    /**
     * Get a default avatar URL that doesn't use Gravatar.
     */
    public function getDefaultAvatarUrl(): string
    {
        // Option 1: Use a solid color avatar with initials (recommended)
        return "https://ui-avatars.com/api/" . urlencode($this->name) . "/150/0066CC/ffffff/2/0.6/1/1";
        
        // Option 2: If you prefer no avatar, return null and handle in template
        // return null;
        
        // Option 3: Use a default static image
        // return asset('images/default-avatar.png');
    }

    /**
     * Check if user has retweeted a specific tweet.
     */
    public function hasRetweeted(Tweet $tweet): bool
    {
        return $this->retweets()->where('tweet_id', $tweet->id)->exists();
    }
}

@extends('layouts.app')

@section('title', $user->name . ' - TwitterClone')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('home') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
    </div>

    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
                @if($user->profile_picture)
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-blue-500" 
                         src="{{ asset('storage/' . $user->profile_picture) }}?v={{ $user->updated_at->timestamp }}" 
                         alt="{{ $user->display_name }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold" style="display:none;">
                        {{ $user->initials }}
                    </div>
                @else
                    <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ $user->initials }}
                    </div>
                @endif
                
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    @if($user->username)
                        <p class="text-gray-600">{{ '@' . $user->username }}</p>
                    @endif
                    
                    @if($user->bio)
                        <p class="text-gray-800 mt-2">{{ $user->bio }}</p>
                    @endif
                    
                    <p class="text-gray-600 mt-1">
                        Joined {{ $user->created_at->format('F Y') }}
                    </p>
                    
                    <div class="flex items-center space-x-6 mt-4">
                        <a href="{{ route('tweets.index') }}" class="text-center hover:underline">
                            <div class="text-xl font-bold text-gray-900">{{ $tweetsCount }}</div>
                            <div class="text-sm text-gray-600">{{ Str::plural('Tweet', $tweetsCount) }}</div>
                        </a>
                        <a href="{{ route('users.following', $user) }}" class="text-center hover:underline">
                            <div class="text-xl font-bold text-gray-900">{{ $followingCount }}</div>
                            <div class="text-sm text-gray-600">Following</div>
                        </a>
                        <a href="{{ route('users.followers', $user) }}" class="text-center hover:underline">
                            <div class="text-xl font-bold text-gray-900">{{ $followersCount }}</div>
                            <div class="text-sm text-gray-600">{{ Str::plural('Follower', $followersCount) }}</div>
                        </a>
                        <a href="{{ route('users.retweets', $user) }}" class="text-center hover:underline">
                            <div class="text-xl font-bold text-gray-900">{{ $user->retweets()->count() }}</div>
                            <div class="text-sm text-gray-600">{{ Str::plural('Retweet', $user->retweets()->count()) }}</div>
                        </a>
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ $totalLikes }}</div>
                            <div class="text-sm text-gray-600">Total {{ Str::plural('Like', $totalLikes) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                @auth
                    @if($user->id === auth()->id())
                        <!-- Edit Profile for own profile -->
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Profile
                        </a>
                    @else
                        <!-- Follow/Unfollow Button -->
                        <form action="{{ route('follow.toggle', $user) }}" method="POST" class="inline">
                            @csrf
                            @if(auth()->user()->isFollowing($user))
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-full text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                    Unfollow
                                </button>
                            @else
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-500 border border-blue-500 rounded-full text-sm font-medium text-white hover:bg-blue-600 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Follow
                                </button>
                            @endif
                        </form>
                        
                        {{-- Message feature disabled --}}
                        {{-- <a href="{{ route('messages.conversation', $user) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Message
                        </a> --}}
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- User's Tweets -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            @if($user->id === auth()->id())
                Your Tweets
            @else
                {{ $user->name }}'s Tweets
            @endif
        </h2>

        @forelse($tweets as $tweet)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start space-x-3">
                    @if($user->profile_picture)
                        <img class="w-12 h-12 rounded-full object-cover" 
                             src="{{ asset('storage/' . $user->profile_picture) }}?v={{ $user->updated_at->timestamp }}" 
                             alt="{{ $user->display_name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold" style="display:none;">
                            {{ $user->initials }}
                        </div>
                    @else
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ $user->initials }}
                        </div>
                    @endif
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-gray-900">{{ $user->display_name }}</span>
                            @if($user->username)
                                <span class="text-gray-500">{{ '@' . $user->username }}</span>
                            @endif
                            @if($user->id === auth()->id())
                                <span class="text-blue-500 text-sm font-medium">You</span>
                            @endif
                            <span class="text-gray-500 text-sm">
                                {{ $tweet->created_at->diffForHumans() }}
                            </span>
                            @if($tweet->is_edited)
                                <span class="text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-full">Edited</span>
                            @endif
                        </div>
                        
                        <div class="mt-2">
                            <p class="text-gray-800 leading-relaxed">{{ $tweet->content }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-8">
                                <!-- Like Button -->
                                <div class="flex items-center space-x-3">
                                    @auth
                                        <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition group">
                                                <div class="p-2 rounded-full group-hover:bg-red-50 transition-colors duration-200">
                                                    <svg class="w-5 h-5 {{ $tweet->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : '' }}" 
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                </div>
                                            </button>
                                        </form>
                                    @else
                                        <div class="flex items-center space-x-2 text-gray-500">
                                            <div class="p-2 rounded-full">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endauth
                                    
                                    @if($tweet->likes->count() > 0)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium {{ auth()->check() && $tweet->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500' }}">
                                                {{ $tweet->likes->count() }}
                                            </span>
                                            <a href="{{ route('tweets.likes', $tweet) }}" 
                                               class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors duration-200">
                                                View
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-sm font-medium text-gray-500">0</span>
                                    @endif
                                </div>
                                
                                <!-- Retweet Button -->
                                <div class="flex items-center space-x-3">
                                    @auth
                                        @if($tweet->user_id !== auth()->id())
                                            <form action="{{ route('retweets.toggle', $tweet) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="flex items-center space-x-2 text-gray-500 hover:text-green-500 transition group">
                                                    <div class="p-2 rounded-full group-hover:bg-green-50 transition-colors duration-200">
                                                        <svg class="w-5 h-5 {{ $tweet->isRetweetedBy(auth()->user()) ? 'text-green-500' : '' }}" 
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex items-center space-x-2 text-gray-500">
                                                <div class="p-2 rounded-full">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="flex items-center space-x-2 text-gray-500">
                                            <div class="p-2 rounded-full">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endauth
                                    
                                    @if($tweet->retweets->count() > 0)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium {{ auth()->check() && $tweet->isRetweetedBy(auth()->user()) ? 'text-green-500' : 'text-gray-500' }}">
                                                {{ $tweet->retweets->count() }}
                                            </span>
                                            <a href="{{ route('tweets.retweets', $tweet) }}" 
                                               class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors duration-200">
                                                View
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-sm font-medium text-gray-500">0</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Edit/Delete buttons for owner -->
                            @auth
                                @if($tweet->user_id === auth()->id())
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('tweets.edit', $tweet) }}" 
                                           class="text-gray-500 hover:text-blue-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('tweets.destroy', $tweet) }}" 
                                              method="POST" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-500 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if($user->id === auth()->id())
                            You haven't posted any tweets yet
                        @else
                            {{ $user->name }} hasn't posted any tweets yet
                        @endif
                    </h3>
                    <p class="text-gray-600">
                        @if($user->id === auth()->id())
                            Share your thoughts with the world!
                        @else
                            Check back later for updates.
                        @endif
                    </p>
                    @if($user->id === auth()->id())
                        <a href="{{ route('home') }}" 
                           class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                            Post Your First Tweet
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
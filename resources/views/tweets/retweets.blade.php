@extends('layouts.app')

@section('title', 'Tweet Retweets')

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

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <h1 class="text-2xl font-bold text-gray-900">{{ $retweets->count() }} {{ Str::plural('Retweet', $retweets->count()) }}</h1>
        </div>
        
        <!-- Original Tweet Preview -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
            <div class="flex items-start space-x-3">
                @if($tweet->user->profile_picture)
                    <img class="w-10 h-10 rounded-full object-cover" 
                         src="{{ $tweet->user->profile_picture_url }}" 
                         alt="{{ $tweet->user->display_name }}">
                @else
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ $tweet->user->initials }}
                    </div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-900">{{ $tweet->user->display_name }}</span>
                        @if($tweet->user->username)
                            <span class="text-gray-500">{{ '@' . $tweet->user->username }}</span>
                        @endif
                        <span class="text-gray-500 text-sm">{{ $tweet->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mt-2 text-gray-800">{{ $tweet->content }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Retweets List -->
    <div class="space-y-3">
        @forelse($retweets as $retweet)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($retweet->user->profile_picture)
                            <img class="w-12 h-12 rounded-full object-cover" 
                                 src="{{ $retweet->user->profile_picture_url }}" 
                                 alt="{{ $retweet->user->display_name }}">
                        @else
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $retweet->user->initials }}
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('users.profile', $retweet->user) }}" 
                                   class="font-bold text-gray-900 hover:text-blue-500 transition">
                                    {{ $retweet->user->display_name }}
                                </a>
                                @if($retweet->user->username)
                                    <span class="text-gray-500">{{ '@' . $retweet->user->username }}</span>
                                @endif
                                @auth
                                    @if($retweet->user_id === auth()->id())
                                        <span class="text-blue-500 text-sm font-medium">You</span>
                                    @endif
                                @endauth
                            </div>
                            @if($retweet->user->bio)
                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($retweet->user->bio, 60) }}</p>
                            @endif
                            <p class="text-gray-500 text-xs mt-1">Retweeted {{ $retweet->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <!-- Follow Button -->
                    @auth
                        @if($retweet->user_id !== auth()->id())
                            <form action="{{ route('follow.toggle', $retweet->user) }}" method="POST" class="inline">
                                @csrf
                                @if(auth()->user()->isFollowing($retweet->user))
                                    <button type="submit" 
                                            class="px-4 py-2 border border-red-300 rounded-full text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition">
                                        Unfollow
                                    </button>
                                @else
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-500 border border-blue-500 rounded-full text-sm font-medium text-white hover:bg-blue-600 transition">
                                        Follow
                                    </button>
                                @endif
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No retweets yet</h3>
                    <p class="text-gray-600">This tweet hasn't been retweeted by anyone yet.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
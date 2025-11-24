@extends('layouts.app')

@section('title', 'Tweet - TwitterClone')

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

    <!-- Tweet -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start space-x-3">
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('users.profile', $tweet->user) }}" 
                       class="font-bold text-gray-900 hover:text-blue-500 transition">
                        {{ $tweet->user->name }}
                    </a>
                    <span class="text-gray-500 text-sm">
                        {{ $tweet->created_at->format('M j, Y \a\t g:i A') }}
                    </span>
                    @if($tweet->is_edited)
                        <span class="text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-full">
                            Edited {{ $tweet->edited_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
                
                <div class="mt-4">
                    <p class="text-gray-800 text-lg leading-relaxed">{{ $tweet->content }}</p>
                </div>
                
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-8">
                        <!-- Like Button -->
                        <div class="flex items-center space-x-3">
                            @auth
                                <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition group">
                                        <svg class="w-6 h-6 {{ $tweet->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : '' }}" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                            @endauth
                            
                            @if($tweet->likes->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium {{ auth()->check() && $tweet->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-700' }}">
                                        {{ $tweet->likes->count() }} {{ Str::plural('like', $tweet->likes->count()) }}
                                    </span>
                                    <a href="{{ route('tweets.likes', $tweet) }}" 
                                       class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors duration-200">
                                        View
                                    </a>
                                </div>
                            @else
                                <span class="font-medium text-gray-700">0 likes</span>
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
                                            <svg class="w-6 h-6 {{ $tweet->isRetweetedBy(auth()->user()) ? 'text-green-500' : '' }}" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center space-x-2 text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                            @endauth
                            
                            @if($tweet->retweets->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium {{ auth()->check() && $tweet->isRetweetedBy(auth()->user()) ? 'text-green-500' : 'text-gray-700' }}">
                                        {{ $tweet->retweets->count() }} {{ Str::plural('retweet', $tweet->retweets->count()) }}
                                    </span>
                                    <a href="{{ route('tweets.retweets', $tweet) }}" 
                                       class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors duration-200">
                                        View
                                    </a>
                                </div>
                            @else
                                <span class="font-medium text-gray-700">0 retweets</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Edit/Delete buttons for owner -->
                    @auth
                        @if($tweet->user_id === auth()->id())
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('tweets.edit', $tweet) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                
                                <form action="{{ route('tweets.destroy', $tweet) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
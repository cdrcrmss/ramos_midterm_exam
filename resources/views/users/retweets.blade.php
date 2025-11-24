@extends('layouts.app')

@section('title', $user->name . "'s Retweets - TwitterClone")

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('users.profile', $user) }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Profile
        </a>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-4">
            @if($user->profile_picture)
                <img class="w-16 h-16 rounded-full object-cover border-4 border-green-500" 
                     src="{{ $user->profile_picture_url }}" 
                     alt="{{ $user->display_name }}">
            @else
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold border-4 border-green-500">
                    {{ $user->initials }}
                </div>
            @endif
            
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center space-x-3">
                    <span>{{ $user->name }}'s Retweets</span>
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </h1>
                @if($user->username)
                    <p class="text-gray-600">{{ '@' . $user->username }}</p>
                @endif
                <p class="text-gray-500 mt-2">{{ $retweets->total() }} {{ Str::plural('Retweet', $retweets->total()) }}</p>
            </div>
        </div>
    </div>

    <!-- Retweets Feed -->
    <div class="space-y-4">
        @forelse($retweets as $retweet)
            <!-- Retweet Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-2 text-green-600 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="text-sm font-medium">
                        @if($user->id === auth()->id())
                            You retweeted
                        @else
                            {{ $user->display_name }} retweeted
                        @endif
                    </span>
                    <span class="text-gray-500 text-sm">{{ $retweet->created_at->diffForHumans() }}</span>
                </div>
                
                <!-- Original Tweet -->
                <div class="border-l-4 border-gray-200 pl-4">
                    <div class="flex items-start space-x-4">
                        @if($retweet->tweet->user->profile_picture)
                            <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 flex-shrink-0" 
                                 src="{{ $retweet->tweet->user->profile_picture_url }}" 
                                 alt="{{ $retweet->tweet->user->display_name }}">
                        @else
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ $retweet->tweet->user->initials }}
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('users.profile', $retweet->tweet->user) }}" 
                                   class="font-bold text-gray-900 hover:text-blue-500 transition-colors duration-200">
                                    {{ $retweet->tweet->user->display_name }}
                                </a>
                                @if($retweet->tweet->user->username)
                                    <span class="text-gray-500">{{ '@' . $retweet->tweet->user->username }}</span>
                                @endif
                                @auth
                                    @if($retweet->tweet->user_id === auth()->id())
                                        <span class="text-blue-500 text-sm font-medium">You</span>
                                    @endif
                                @endauth
                                <span class="text-gray-500 text-sm">
                                    {{ $retweet->tweet->created_at->diffForHumans() }}
                                </span>
                                @if($retweet->tweet->is_edited)
                                    <span class="text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-full">Edited</span>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <p class="text-gray-800 leading-relaxed text-lg">{{ $retweet->tweet->content }}</p>
                            </div>
                            
                            <div class="flex items-center justify-between mt-6">
                                <div class="flex items-center space-x-8">
                                    <!-- Like Button -->
                                    <div class="flex items-center space-x-3">
                                        @auth
                                            <form action="{{ route('likes.toggle', $retweet->tweet) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors duration-200 group">
                                                    <div class="p-2 rounded-full group-hover:bg-red-50 transition-colors duration-200">
                                                        <svg class="w-5 h-5 {{ $retweet->tweet->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : '' }}" 
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
                                        
                                        @if($retweet->tweet->likes->count() > 0)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium {{ auth()->check() && $retweet->tweet->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500' }}">
                                                    {{ $retweet->tweet->likes->count() }}
                                                </span>
                                                <a href="{{ route('tweets.likes', $retweet->tweet) }}" 
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
                                            @if($retweet->tweet->user_id !== auth()->id())
                                                <form action="{{ route('retweets.toggle', $retweet->tweet) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="flex items-center space-x-2 text-gray-500 hover:text-green-500 transition-colors duration-200 group">
                                                        <div class="p-2 rounded-full group-hover:bg-green-50 transition-colors duration-200">
                                                            <svg class="w-5 h-5 {{ $retweet->tweet->isRetweetedBy(auth()->user()) ? 'text-green-500' : '' }}" 
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
                                        
                                        @if($retweet->tweet->retweets->count() > 0)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium {{ auth()->check() && $retweet->tweet->isRetweetedBy(auth()->user()) ? 'text-green-500' : 'text-gray-500' }}">
                                                    {{ $retweet->tweet->retweets->count() }}
                                                </span>
                                                <a href="{{ route('tweets.retweets', $retweet->tweet) }}" 
                                                   class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors duration-200">
                                                    View
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-sm font-medium text-gray-500">0</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if($user->id === auth()->id())
                            You haven't retweeted anything yet
                        @else
                            {{ $user->name }} hasn't retweeted anything yet
                        @endif
                    </h3>
                    <p class="text-gray-600">
                        @if($user->id === auth()->id())
                            When you retweet posts, they'll appear here.
                        @else
                            Check back later for updates.
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($retweets->hasPages())
        <div class="mt-8">
            {{ $retweets->links() }}
        </div>
    @endif
</div>
@endsection
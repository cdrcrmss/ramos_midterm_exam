@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tweet Search</h1>
        @if(request('q'))
            <p class="text-gray-600">Results for "{{ request('q') }}"</p>
        @else
            <p class="text-gray-600">Search for tweets</p>
        @endif
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('search.tweets') }}" method="GET">
            <div class="flex space-x-4">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Search for tweets..." 
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    @if($tweets->isNotEmpty())
        <div class="space-y-4">
            @foreach($tweets as $tweet)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start space-x-3">
                        @if($tweet->user->profile_picture)
                            <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200" 
                                 src="{{ $tweet->user->profile_picture_url }}" 
                                 alt="{{ $tweet->user->display_name }}">
                        @else
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $tweet->user->initials }}
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('users.profile', $tweet->user) }}" class="font-bold text-gray-900 hover:underline">
                                    {{ $tweet->user->display_name }}
                                </a>
                                @if($tweet->user->username)
                                    <span class="text-gray-500">{{ '@' . $tweet->user->username }}</span>
                                @endif
                                <span class="text-gray-500 text-sm">
                                    {{ $tweet->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <div class="mt-2">
                                <p class="text-gray-800 leading-relaxed">{{ $tweet->content }}</p>
                            </div>
                            
                            <div class="flex items-center space-x-6 mt-4">
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span>{{ $tweet->likes->count() }}</span>
                                </div>
                                
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span>{{ $tweet->retweets->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($tweets->hasPages())
            <div class="mt-8">
                {{ $tweets->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            @if(request('q'))
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No tweets found</h3>
                <p class="text-gray-600">Try searching for different keywords</p>
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Search Tweets</h3>
                <p class="text-gray-600">Find interesting tweets by searching for keywords</p>
            @endif
        </div>
    @endif
</div>
@endsection
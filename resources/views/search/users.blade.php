@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">People Search</h1>
        @if(request('q'))
            <p class="text-gray-600">Results for "{{ request('q') }}"</p>
        @else
            <p class="text-gray-600">Discover people to follow</p>
        @endif
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('search.users') }}" method="GET">
            <div class="flex space-x-4">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Search for people..." 
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

    @if($users->isNotEmpty())
        <div class="grid gap-4">
            @foreach($users as $user)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if($user->profile_picture)
                                    <img class="w-16 h-16 rounded-full object-cover border-2 border-gray-200" 
                                         src="{{ $user->profile_picture_url }}" 
                                         alt="{{ $user->display_name }}">
                                @else
                                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                        {{ $user->initials }}
                                    </div>
                                @endif                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $user->display_name }}</h3>
                                    @if($user->username)
                                        <span class="text-gray-500">{{ '@' . $user->username }}</span>
                                    @endif
                                </div>
                                @if($user->bio)
                                    <p class="text-gray-600 mt-2">{{ $user->bio }}</p>
                                @endif
                                <div class="flex items-center space-x-6 mt-3 text-sm text-gray-500">
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <span>{{ $user->tweets_count ?? 0 }} tweets</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                        <span>{{ $user->followers_count ?? 0 }} followers</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>{{ $user->following_count ?? 0 }} following</span>
                                    </span>
                                    <span class="text-gray-400">•</span>
                                    <span>Joined {{ $user->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('users.profile', $user) }}" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                View Profile
                            </a>
                            
                            @auth
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('follow.toggle', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @if(auth()->user()->isFollowing($user))
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition">
                                                Unfollow
                                            </button>
                                        @else
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition">
                                                Follow
                                            </button>
                                        @endif
                                    </form>
                                    
                                    {{-- Message feature disabled --}}
                                    {{-- <a href="{{ route('messages.conversation', $user) }}" 
                                       class="px-4 py-2 border border-blue-500 text-blue-500 font-semibold rounded-lg hover:bg-blue-50 transition">
                                        Message
                                    </a> --}}
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($users->hasPages())
            <div class="mt-8">
                {{ $users->appends(request()->query())->links() }}
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
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No people found</h3>
                <p class="text-gray-600">Try searching for a different name or username</p>
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Discover People</h3>
                <p class="text-gray-600">Search to find interesting people to follow</p>
            @endif
        </div>
    @endif
</div>
@endsection
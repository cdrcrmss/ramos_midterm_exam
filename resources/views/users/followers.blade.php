@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('users.profile', $user) }}" 
               class="text-gray-500 hover:text-blue-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->display_name }}</h1>
                @if($user->username)
                    <p class="text-gray-600">{{ '@' . $user->username }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6">
                <a href="{{ route('users.followers', $user) }}" 
                   class="py-4 px-1 border-b-2 {{ request()->routeIs('users.followers') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition">
                    Followers
                    <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                        {{ $user->followers->count() }}
                    </span>
                </a>
                <a href="{{ route('users.following', $user) }}" 
                   class="py-4 px-1 border-b-2 {{ request()->routeIs('users.following') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition">
                    Following
                    <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                        {{ $user->following->count() }}
                    </span>
                </a>
            </nav>
        </div>
    </div>

    @if($followers->isNotEmpty())
        <div class="space-y-4">
            @foreach($followers as $follower)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if($follower->profile_picture)
                                <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200" 
                                     src="{{ $follower->profile_picture_url }}" 
                                     alt="{{ $follower->display_name }}">
                            @else
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ $follower->initials }}
                                </div>
                            @endif
                            
                            <div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('users.profile', $follower) }}" class="font-bold text-gray-900 hover:underline">
                                        {{ $follower->display_name }}
                                    </a>
                                    @if($follower->username)
                                        <span class="text-gray-500">{{ '@' . $follower->username }}</span>
                                    @endif
                                </div>
                                @if($follower->bio)
                                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($follower->bio, 100) }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <span>{{ $follower->tweets->count() }} tweets</span>
                                    <span>{{ $follower->followers->count() }} followers</span>
                                    <span>{{ $follower->following->count() }} following</span>
                                </div>
                            </div>
                        </div>
                        
                        @auth
                            @if($follower->id !== auth()->id())
                                <div class="flex items-center space-x-3">
                                    <form action="{{ route('follow.toggle', $follower) }}" method="POST" class="inline">
                                        @csrf
                                        @if(auth()->user()->isFollowing($follower))
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
                                    
                                    {{-- Temporarily disabled message button --}}
                                    {{-- <a href="{{ route('messages.conversation', $follower) }}" 
                                       class="px-4 py-2 border border-blue-500 text-blue-500 font-semibold rounded-lg hover:bg-blue-50 transition">
                                        Message
                                    </a> --}}
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($followers->hasPages())
            <div class="mt-8">
                {{ $followers->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            @if($user->id === auth()->id())
                <h3 class="text-xl font-semibold text-gray-900 mb-2">You don't have any followers yet</h3>
                <p class="text-gray-600">When people follow you, they'll show up here</p>
            @else
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $user->display_name }} doesn't have any followers yet</h3>
                <p class="text-gray-600">When people follow them, they'll show up here</p>
            @endif
        </div>
    @endif
</div>
@endsection
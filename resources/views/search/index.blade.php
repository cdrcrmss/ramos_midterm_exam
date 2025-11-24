@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Search</h1>
        <p class="text-gray-600">Find people</p>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('search.index') }}" method="GET" class="space-y-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Search for people..." 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
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

    @if(request('q'))
        <div class="space-y-6">
            <!-- Users Results -->
            @if($users->isNotEmpty())
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">People ({{ $users->total() }} results)</h2>
                    <div class="grid gap-4">
                        @foreach($users as $user)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        @if($user->profile_picture)
                                            <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200" 
                                                 src="{{ $user->profile_picture_url }}" 
                                                 alt="{{ $user->display_name }}">
                                        @else
                                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ $user->initials }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <h3 class="font-bold text-gray-900">{{ $user->display_name }}</h3>
                                                @if($user->username)
                                                    <span class="text-gray-500">{{ '@' . $user->username }}</span>
                                                @endif
                                            </div>
                                            @if($user->bio)
                                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($user->bio, 100) }}</p>
                                            @endif
                                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                                <span>{{ $user->tweets_count }} tweets</span>
                                                <span>{{ $user->followers_count }} followers</span>
                                                <span>{{ $user->following_count }} following</span>
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
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- No Results -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-600">Try searching for something else</p>
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Search People</h3>
            <p class="text-gray-600">Find people to follow and connect with</p>
        </div>
    @endif
</div>
@endsection
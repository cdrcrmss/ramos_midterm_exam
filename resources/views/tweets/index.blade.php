@extends('layouts.app')

@section('title', 'Home - TwitterClone')

@section('content')
<div class="max-w-2xl mx-auto">
    @auth
        <!-- Tweet Composer -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6 hover:shadow-md transition-shadow duration-200">
            <form action="{{ route('tweets.store') }}" method="POST">
                @csrf
                <div class="flex space-x-4">
                    @if(auth()->user()->profile_picture_url)
                        <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 flex-shrink-0" 
                             src="{{ auth()->user()->profile_picture_url }}" 
                             alt="{{ auth()->user()->display_name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0" style="display:none;">
                            {{ auth()->user()->initials }}
                        </div>
                    @else
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ auth()->user()->initials }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <textarea id="content"
                                  name="content" 
                                  rows="4" 
                                  maxlength="280"
                                  placeholder="What's happening?"
                                  class="w-full p-4 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none text-lg placeholder-gray-500 @error('content') border-red-500 @enderror"
                                  oninput="updateCharCount()">{{ old('content') }}</textarea>
                        
                        @error('content')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex items-center space-x-4">
                                <span id="charCount" class="text-sm font-medium text-gray-500">0/280</span>
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <button type="submit"
                                    id="tweetButton"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                                Tweet
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @else
        <!-- Welcome message for guests -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg p-8 mb-6 text-center text-white">
            <div class="mb-4">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-90" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold mb-4">Welcome to TwitterClone</h2>
            <p class="text-xl mb-8 opacity-90">Join the conversation and share what's on your mind</p>
            <div class="space-x-4">
                <a href="{{ route('register') }}" 
                   class="bg-white text-blue-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                    Sign Up
                </a>
                <a href="{{ route('login') }}" 
                   class="border-2 border-white text-white hover:bg-white hover:text-blue-600 font-bold py-3 px-8 rounded-full transition-all duration-200">
                    Login
                </a>
            </div>
        </div>
    @endauth

    <!-- Tweets Feed -->
    <div class="space-y-4">
        @forelse($tweets as $tweet)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
                <div class="flex items-start space-x-4">
                    @if($tweet->user->profile_picture_url)
                        <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 flex-shrink-0" 
                             src="{{ $tweet->user->profile_picture_url }}" 
                             alt="{{ $tweet->user->display_name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0" style="display:none;">
                            {{ $tweet->user->initials }}
                        </div>
                    @else
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ $tweet->user->initials }}
                        </div>
                    @endif
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('users.profile', $tweet->user) }}" 
                               class="font-bold text-gray-900 hover:text-blue-500 transition-colors duration-200">
                                {{ $tweet->user->display_name }}
                            </a>
                            @if($tweet->user->username)
                                <span class="text-gray-500">{{ '@' . $tweet->user->username }}</span>
                            @endif
                            @auth
                                @if($tweet->user_id === auth()->id())
                                    <span class="text-blue-500 text-sm font-medium">You</span>
                                @endif
                            @endauth
                            <span class="text-gray-500 text-sm">
                                {{ $tweet->created_at->diffForHumans() }}
                            </span>
                            @if($tweet->is_edited)
                                <span class="text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-full">Edited</span>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <p class="text-gray-800 leading-relaxed text-lg">{{ $tweet->content }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center space-x-8">
                                <!-- Like Button -->
                                <div class="flex items-center space-x-3">
                                    @auth
                                        <form action="{{ route('likes.toggle', $tweet) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors duration-200 group">
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
                                                        class="flex items-center space-x-2 text-gray-500 hover:text-green-500 transition-colors duration-200 group">
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
                                           class="p-2 text-gray-500 hover:text-blue-500 hover:bg-blue-50 rounded-full transition-all duration-200">
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
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full transition-all duration-200">
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
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="text-gray-400">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">No tweets yet</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">
                        @auth
                            Be the first to share something! What's on your mind?
                        @else
                            Sign up to start sharing your thoughts and join the conversation.
                        @endauth
                    </p>
                    @guest
                        <div class="mt-8 space-x-4">
                            <a href="{{ route('register') }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-full transition-all duration-200 shadow-sm hover:shadow-md">
                                Get Started
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tweets->hasPages())
        <div class="mt-8">
            {{ $tweets->links() }}
        </div>
    @endif
</div>

<script>
function updateCharCount() {
    const textarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    const tweetButton = document.getElementById('tweetButton');
    const currentLength = textarea.value.length;
    
    charCount.textContent = currentLength + '/280';
    
    if (currentLength > 280) {
        charCount.classList.add('text-red-500');
        charCount.classList.remove('text-gray-500');
        tweetButton.disabled = true;
    } else if (currentLength === 0) {
        charCount.classList.add('text-gray-500');
        charCount.classList.remove('text-red-500');
        tweetButton.disabled = true;
    } else {
        charCount.classList.add('text-gray-500');
        charCount.classList.remove('text-red-500');
        tweetButton.disabled = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
});
</script>
@endsection
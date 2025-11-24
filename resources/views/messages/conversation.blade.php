@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('messages.index') }}" 
                   class="text-gray-500 hover:text-blue-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                
                <div class="flex items-center space-x-3">
                    @if($user->profile_picture)
                        <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200" 
                             src="{{ $user->profile_picture_url }}" 
                             alt="{{ $user->display_name }}">
                    @else
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ $user->initials }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $user->display_name }}</h1>
                        @if($user->username)
                            <p class="text-gray-500 text-sm">{{ '@' . $user->username }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <a href="{{ route('users.profile', $user) }}" 
               class="text-gray-500 hover:text-blue-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-96">
        <!-- Messages -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800' }}">
                        <p class="text-sm">{{ $message->content }}</p>
                        <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                            {{ $message->created_at->format('g:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 mt-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p>No messages yet</p>
                    <p class="text-sm">Start the conversation below</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 p-4">
            <form action="{{ route('messages.send', $user) }}" method="POST" class="flex space-x-3">
                @csrf
                <input type="text" 
                       name="content" 
                       placeholder="Type your message..." 
                       required
                       maxlength="1000"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });
    
    // Auto-scroll to bottom when form is submitted
    document.querySelector('form').addEventListener('submit', function() {
        setTimeout(() => {
            const messagesContainer = document.getElementById('messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    });
</script>
@endsection
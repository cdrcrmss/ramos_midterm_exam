@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Messages</h1>
        <p class="text-gray-600">Your direct message conversations</p>
    </div>

    @if($conversations->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No messages yet</h3>
            <p class="text-gray-600 mb-6">Start a conversation by sending a message to someone</p>
            <a href="{{ route('search.users') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Messaging feature disabled
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($conversations as $conversation)
                @php
                    $otherUser = $conversation['user'];
                    $lastMessage = $conversation['last_message'];
                    $unreadCount = $conversation['unread_count'];
                @endphp
                
                <a href="{{ route('messages.conversation', $otherUser) }}" 
                   class="block bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition group">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            @if($otherUser->profile_picture)
                                <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200" 
                                     src="{{ $otherUser->profile_picture_url }}" 
                                     alt="{{ $otherUser->display_name }}">
                            @else
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ $otherUser->initials }}
                                </div>
                            @endif
                            @if($unreadCount > 0)
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                    <span class="text-xs text-white font-bold">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-gray-900 {{ $unreadCount > 0 ? 'text-gray-900' : 'text-gray-700' }}">
                                        {{ $otherUser->display_name }}
                                    </span>
                                    @if($otherUser->username)
                                        <span class="text-gray-500">{{ '@' . $otherUser->username }}</span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                </span>
                            </div>
                            
                            @if($lastMessage)
                                <p class="mt-1 text-gray-600 text-sm truncate {{ $unreadCount > 0 ? 'font-medium' : '' }}">
                                    @if($lastMessage->sender_id === auth()->id())
                                        <span class="text-gray-500">You:</span>
                                    @endif
                                    {{ $lastMessage->content }}
                                </p>
                            @else
                                <p class="mt-1 text-gray-400 text-sm italic">Start a conversation</p>
                            @endif
                        </div>
                        
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if($conversations->hasPages())
            <div class="mt-8">
                {{ $conversations->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
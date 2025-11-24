@extends('layouts.app')

@section('title', 'Edit Tweet - TwitterClone')

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

    <!-- Edit Tweet Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Edit Tweet</h2>
        
        <form action="{{ route('tweets.update', $tweet) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Tweet Content
                </label>
                <textarea id="content"
                          name="content" 
                          rows="6" 
                          maxlength="280"
                          placeholder="What's happening?"
                          class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('content') border-red-500 @enderror"
                          oninput="updateCharCount()">{{ old('content', $tweet->content) }}</textarea>
                
                @error('content')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-between items-center mt-4">
                <span id="charCount" class="text-sm text-gray-500">0/280</span>
                <div class="flex space-x-3">
                    <a href="{{ route('tweets.show', $tweet) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            id="updateButton"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Update Tweet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateCharCount() {
    const textarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    const updateButton = document.getElementById('updateButton');
    const currentLength = textarea.value.length;
    
    charCount.textContent = currentLength + '/280';
    
    if (currentLength > 280) {
        charCount.classList.add('text-red-500');
        charCount.classList.remove('text-gray-500');
        updateButton.disabled = true;
    } else if (currentLength === 0) {
        charCount.classList.add('text-gray-500');
        charCount.classList.remove('text-red-500');
        updateButton.disabled = true;
    } else {
        charCount.classList.add('text-gray-500');
        charCount.classList.remove('text-red-500');
        updateButton.disabled = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
});
</script>
@endsection
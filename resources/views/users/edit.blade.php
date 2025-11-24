@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Profile Picture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Profile Picture</label>
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if($user->profile_picture)
                                <img class="w-20 h-20 rounded-full object-cover border-4 border-gray-200" 
                                     src="{{ $user->profile_picture_url }}" 
                                     alt="{{ $user->display_name }}"
                                     id="current-profile-picture">
                            @else
                                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-gray-200"
                                     id="profile-placeholder">
                                    {{ $user->initials }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <label for="profile-picture-input" 
                                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 cursor-pointer">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Upload File
                                </label>
                                <span id="file-status" class="text-sm text-gray-500">No file chosen</span>
                                <input type="file" 
                                       name="profile_picture" 
                                       accept="image/*"
                                       id="profile-picture-input"
                                       class="hidden"
                                       onchange="previewImage(event); updateFileName(event)">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF up to 2MB</p>
                            @error('profile_picture')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username (Optional)
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username', $user->username) }}"
                           placeholder="Enter a unique username"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-gray-500 text-sm mt-1">Username can only contain letters, numbers, hyphens, and underscores.</p>
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                        Bio (Optional)
                    </label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="4"
                              placeholder="Tell us about yourself..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('bio', $user->bio) }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-gray-500 text-sm">Maximum 500 characters</p>
                        <span class="text-gray-500 text-sm" id="bio-count">{{ strlen($user->bio ?? '') }}/500</span>
                    </div>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('users.profile', $user) }}" 
                       class="text-gray-600 hover:text-gray-800 font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-full font-medium transition-colors duration-200">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(event) {
    const fileInput = event.target;
    const fileStatus = document.getElementById('file-status');
    const fileName = fileInput.files[0]?.name;
    
    if (fileName) {
        // Truncate long filenames
        const displayName = fileName.length > 30 ? fileName.substring(0, 30) + '...' : fileName;
        fileStatus.textContent = displayName;
        fileStatus.classList.remove('text-gray-500');
        fileStatus.classList.add('text-green-600');
    } else {
        fileStatus.textContent = 'No file chosen';
        fileStatus.classList.remove('text-green-600');
        fileStatus.classList.add('text-gray-500');
    }
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const currentImg = document.getElementById('current-profile-picture');
            const placeholder = document.getElementById('profile-placeholder');
            
            if (currentImg) {
                currentImg.src = e.target.result;
            } else if (placeholder) {
                // Replace placeholder with image
                const img = document.createElement('img');
                img.className = 'w-20 h-20 rounded-full object-cover border-4 border-gray-200';
                img.id = 'current-profile-picture';
                img.src = e.target.result;
                img.alt = 'Profile preview';
                placeholder.parentNode.replaceChild(img, placeholder);
            }
        };
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const bioTextarea = document.getElementById('bio');
    const bioCount = document.getElementById('bio-count');
    
    function updateCount() {
        const length = bioTextarea.value.length;
        bioCount.textContent = length + '/500';
        
        if (length > 500) {
            bioCount.classList.add('text-red-500');
            bioCount.classList.remove('text-gray-500');
        } else {
            bioCount.classList.remove('text-red-500');
            bioCount.classList.add('text-gray-500');
        }
    }
    
    bioTextarea.addEventListener('input', updateCount);
});
</script>
@endsection
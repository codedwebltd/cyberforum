<!-- resources/views/components/user-avatar.blade.php -->
<div class="relative flex items-center justify-center {{ $size }} rounded-full overflow-hidden bg-gradient-primary">
    @if($user && ($user->avatar_url ?? $user->avatar))
        <img src="{{ $user->avatar_url ?? $user->avatar }}" alt="Profile" class="w-full h-full object-cover">
    @else
        <span class="text-sm font-bold text-white">{{ $user_initials }}</span>
    @endif
</div>
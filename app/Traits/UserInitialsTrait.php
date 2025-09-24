<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait UserInitialsTrait
{
    protected function getUserAvatarData()
    {
        $user_avatar = null;
        $user_initials = 'GE'; // Default for guests
        
        if (Auth::check()) {
            $user = Auth::user();
            $user_avatar = $user->avatar_url ?? $user->avatar ?? null;
            $user_initials = $this->generateUserInitials($user);
        }
        
        return [
            'user_avatar' => $user_avatar,
            'user_initials' => $user_initials,
        ];
    }
    
    protected function generateUserInitials($user)
    {
        $firstName = $user->first_name ?? $user->name ?? '';
        $lastName = $user->last_name ?? '';
        
        if ($firstName && $lastName) {
            return strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        } elseif ($firstName) {
            $nameParts = explode(' ', trim($firstName));
            if (count($nameParts) >= 2) {
                return strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
            } else {
                return strtoupper(substr($firstName, 0, 2));
            }
        }
        
        // Fallback to email initials
        return strtoupper(substr($user->email ?? '', 0, 2));
    }
}
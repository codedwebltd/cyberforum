<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserAvatar extends Component
{
    public $user;
    public $size;
    
    public function __construct($user = null, $size = 'w-8 h-8')
    {
        $this->user = $user ?? auth()->user();
        $this->size = $size;
    }

    public function render()
    {
        return view('components.user-avatar');
    }
    
    public function getInitials()
    {
        // Handle guest users
        if (!$this->user) {
            return 'GE';
        }
        
        $firstName = $this->user->first_name ?? $this->user->name ?? '';
        $lastName = $this->user->last_name ?? '';
        
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
        
        // Fallback for guests
        return 'GE';
    }
}
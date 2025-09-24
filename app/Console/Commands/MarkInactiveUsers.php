<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MarkInactiveUsers extends Command
{
    protected $signature = 'users:mark-inactive';
    protected $description = 'Mark users as inactive after 30 minutes of inactivity';

    public function handle()
    {
        $threshold = now()->subMinutes(1);
        
        $affected = User::where('last_active_at', '<', $threshold)
            ->where('is_active', true)
            ->update(['is_active' => false]);
            
        $this->info("Marked {$affected} users as inactive");
        return 0;
    }
}
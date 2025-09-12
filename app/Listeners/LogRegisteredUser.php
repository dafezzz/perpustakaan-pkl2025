<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Helpers\ActivityLogger;

class LogRegisteredUser
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        ActivityLogger::log('Register', $user, 'User ' . $user->name . ' mendaftar');
    }
}

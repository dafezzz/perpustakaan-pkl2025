<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Helpers\ActivityLogger;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        $user = $event->user;
        ActivityLogger::log('Logout', $user, 'User ' . $user->name . ' logout');
    }
}

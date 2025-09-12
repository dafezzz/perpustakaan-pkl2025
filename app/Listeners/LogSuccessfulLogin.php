<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Helpers\ActivityLogger;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        ActivityLogger::log('Login', $user, 'User ' . $user->name . ' berhasil login');
    }
}

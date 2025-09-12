<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($activity, $model = null, $details = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'model' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'details' => $details,
            'ip_address' => Request::ip(),
        ]);
    }
}

<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class BaseObserver
{
    protected function log($action, $model)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => $action,
            'model' => get_class($model),
            'model_id' => $model->id ?? null,
            'details' => json_encode($model->getAttributes()),
            'ip_address' => Request::ip(),
        ]);
    }

    public function created($model)
    {
        $this->log('Created', $model);
    }

    public function updated($model)
    {
        $this->log('Updated', $model);
    }

    public function deleted($model)
    {
        $this->log('Deleted', $model);
    }
}

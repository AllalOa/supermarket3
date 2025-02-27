<?php
namespace App\Helpers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    public static function log($action, $model = null)
    {
        Activity::create([
            'user_id'    => auth()->id(), // Store user ID
            'action'     => $action,      // Action description
            'model_type' => $model ? get_class($model) : null, // Store model class
            'model_id'   => $model ? $model->id : null,        // Store model ID
        ]);
    }
}

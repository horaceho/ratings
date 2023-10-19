<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasUpdatedBy
{
    public static function bootHasUpdatedBy()
    {
        static::updating(function (Model $model) {
            $model->updated_by = Auth::id();
        });
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

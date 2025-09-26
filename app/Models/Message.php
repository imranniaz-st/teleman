<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected static function booted()
    {
        $clearCache = function ($message) {
            $userId = $message->user_id;
            $myNumber = $message->my_number;
            $cacheKey = 'messages_for_user_' . $userId . '_number_' . $myNumber;
            Cache::forget($cacheKey);
        };

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }

    protected $guarded = ['id'];

}

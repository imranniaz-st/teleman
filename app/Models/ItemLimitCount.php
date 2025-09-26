<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemLimitCount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Agent
     */
    public function scopeHasAgent($query, $user_id = null)
    {
        if (is_agent($user_id) == true) {
            return $query->where('user_id', getUserInfo($user_id)->id);
        }
        return $query->where('user_id', $user_id);
    }
}

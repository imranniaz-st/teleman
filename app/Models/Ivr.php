<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Ivr extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Protect the id field from mass assignment

    /**
     * Agent
     */
    public function scopeHasAgent($query)
    {
        if (Auth::user()->role == 'agent') {
            return $query->where('user_id', agent_owner_id());
        }
        return $query->where('user_id', Auth::user()->id);
    }
}

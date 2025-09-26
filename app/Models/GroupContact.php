<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class GroupContact extends Model
{
    use HasFactory;

    protected $with = ['contacts'];

    /**
     * Relation with contacts
     */
    public function contacts()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }

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

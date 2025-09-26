<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class CampaignSchedule extends Model
{
    use HasFactory;

    /**
     * group_contacts
     */
    public function contacts()
    {
        return $this->hasMany(GroupContact::class, 'group_id', 'group_id');
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

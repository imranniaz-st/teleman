<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Group extends Model
{
    use HasFactory;

    /**
     * group_contacts
     */
    public function group_contacts()
    {
        return $this->hasMany(GroupContact::class, 'group_id', 'id');
    }

    /**
     * Campiagn
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'group_id', 'id');
    }

    /**
     * CampaignSchedule
     */
    public function campaign_schedules()
    {
        return $this->hasMany(CampaignSchedule::class, 'group_id', 'id');
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

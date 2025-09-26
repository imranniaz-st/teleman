<?php

namespace App\Models;

use Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($contact) {
            Cache::forget('contacts_with_agents');
        });

        static::updated(function ($contact) {
            Cache::forget('contacts_with_agents');
        });

        static::deleted(function ($contact) {
            Cache::forget('contacts_with_agents');
        });
    }

    /* The line `protected  = [];` in the `Contact` model is used to specify which attributes
    are not mass assignable. By setting it to an empty array, it means that all attributes are mass
    assignable, and there are no guarded attributes. This allows you to easily create or update a
    `Contact` instance using the `create()` or `update()` methods without having to specify each
    attribute individually. */
    protected $guarded = [];

    /**
     * Group Contacts
     */
    public function group_contacts()
    {
        return $this->hasMany(GroupContact::class, 'contact_id', 'id');
    }

    /**
     * Campaign Voice
     */
    public function campaign_voice()
    {
        return $this->hasMany(CampaignVoice::class, 'contact_id', 'id');
    }

    /**
     * CampaignVoiceStatusLog
     */
    public function campaign_voice_status_log()
    {
        return $this->hasMany(CampaignVoiceStatusLog::class, 'contact_id', 'id');
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

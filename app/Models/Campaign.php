<?php

namespace App\Models;

use Auth;
use App\Models\SmsContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    /**
     * PROVIDER
     */
    public function provider()
    {
        return $this->hasOne(Provider::class, 'id', 'provider');
    }

    /**
     * CampaignSchedule
     */
    public function campaign_schedules()
    {
        return $this->hasMany(CampaignSchedule::class, 'campaign_id', 'id');
    }

    /**
     * CampaignVoice
     */
    public function campaign_voices()
    {
        return $this->hasMany(CampaignVoice::class, 'campaign_id', 'id');
    }

    /**
     * CampaignVoiceStatusLog
     */
    public function campaign_voice_status_logs()
    {
        return $this->hasMany(CampaignVoiceStatusLog::class, 'campaign_id', 'id');
    }

    /**
     * relation with ivr
     */
    public function ivr()
    {
        return $this->hasOne(Ivr::class, 'campaign_id', 'id');
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

    /**
     * This function returns a collection of SmsSchedule objects associated with a specific campaign
     * ID.
     * 
     * @return A relationship between the current model and the SmsSchedule model is being returned.
     * Specifically, it is a "hasMany" relationship, meaning that the current model can have multiple
     * SmsSchedule instances associated with it. The relationship is defined by matching the
     * "campaign_id" attribute of the SmsSchedule model with the "id" attribute of the current model.
     */
    public function sms_schedules()
    {
        return $this->hasMany(SmsSchedule::class, 'campaign_id', 'id');
    }

    /**
     * This PHP function returns a relationship between the current object and a SmsContent object
     * based on their respective IDs.
     * 
     * @return A relationship between the current model and the SmsContent model is being returned.
     * Specifically, a "hasOne" relationship is being established where the SmsContent model is related
     * to the current model by matching the "campaign_id" column of the SmsContent table with the "id"
     * column of the current model's table.
     */
    public function sms_content()
    {
        return $this->hasOne(SmsContent::class, 'campaign_id', 'id');
    }
}

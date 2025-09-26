<?php

namespace App\Models;

use Auth;
use App\Models\Department;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected static function booted()
    {
        $clearCache = function ($provider) {
            Cache::forget('voice_server_list_admin');
        };

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }
    
    /**
     * > This function returns all the campaigns that belong to this provider
     * 
     * @return A collection of Campaigns
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'provider', 'id');
    }
    
    /**
     * It returns the campaign schedules that belong to the provider.
     * 
     * @return The campaign_schedules() method returns a collection of CampaignSchedule objects.
     */
    public function campaign_schedules()
    {
        return $this->hasMany(CampaignSchedule::class, 'provider', 'id');
    }
    
    /**
     * If the user is an agent, return the query where the user_id is the agent_owner_id, otherwise
     * return the query where the user_id is the current user's id
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeHasAgent($query, $user_id = null)
    {
        if (is_agent($user_id) == true) {
            return $query->where('user_id', agent_owner_id($user_id));
        }
        return $query->where('user_id', $user_id);
    }

    /**
     * This function returns a relationship between the current model and the User model
     * 
     * @return A user object
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * The function "department" returns a relationship where the current object has one instance of
     * the "Department" class, with the foreign key "provider_id" matching the primary key "id" of the
     * current object.
     * 
     * @return a relationship between the current model and the Department model. It is using the
     * `hasOne` method to define the relationship. The first argument is the related model class
     * (`Department::class`), the second argument is the foreign key column in the related model
     * (`provider_id`), and the third argument is the local key column in the current model (`id`).
     */
    public function department()
    {
        return $this->hasOne(Department::class, 'provider_id', 'id');
    }

    // ENDS
}

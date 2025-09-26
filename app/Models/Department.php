<?php

namespace App\Models;

use App\Models\User;
use App\Models\Provider;
use App\Models\DepartmentProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'json' => 'array',
        'channel' => 'array'
    ];

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
     * The function returns a relationship between the current object and a Provider object.
     * 
     * @return a relationship between the current model and the Provider model. It is using the hasOne
     * method to define the relationship, specifying that the foreign key on the Provider model is 'id'
     * and the local key on the current model is 'provider_id'.
     */
    public function provider(): HasOne
    {
        return $this->hasOne(DepartmentProvider::class, 'department_id', 'id');
    }

    /**
     * The function "providers" returns a HasMany relationship for the DepartmentProvider model, using
     * the "department_id" as the foreign key and "id" as the local key.
     * 
     * @return HasMany a HasMany relationship.
     */
    public function providers(): HasMany
    {
        return $this->hasMany(DepartmentProvider::class, 'department_id', 'id');
    }

    // department has many agents
    public function agents()
    {
        return $this->hasMany(DepartmentAgent::class, 'department_id', 'id');
    }

    // outbound scope
    public function scopeOutbound($query)
    {
        return $query->where('outbound', 1);
    }

}

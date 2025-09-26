<?php

namespace App\Models;

use Auth;
use App\Models\Identity;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * > The subscription() function returns a relationship between the User model and the Subscription
     * model
     * 
     * @return A single instance of the Subscription model.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'id');
    }

    /**
     * It returns the item_limit_count table.
     * 
     * @return The item_limit_count() method returns the item_limit_count relationship.
     */
    public function item_limit_count()
    {
        return $this->hasOne(ItemLimitCount::class, 'user_id', 'id');
    }

    /**
     * > The `payment_histories()` function returns a collection of `PaymentHistory` objects that
     * belong to the `User` object
     * 
     * @return A collection of PaymentHistory objects.
     */
    public function payment_histories()
    {
        return $this->hasMany(PaymentHistory::class, 'user_id', 'id');
    }

    /**
     * The `agent()` function returns the `Agent` model that belongs to the `User` model
     * 
     * @return The agent() method returns the agent that belongs to the user.
     */
    public function agent()
    {
        return $this->hasOne(Agent::class, 'user_id', 'id');
    }
    
    /**
     * If the user is an agent, return the query where the user_id is the agent_owner_id, otherwise
     * return the query where the user_id is the current user's id
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeHasAgent($query)
    {
        if (Auth::user()->role == 'agent') {
            return $query->where('user_id', agent_owner_id());
        }
        return $query->where('user_id', Auth::user()->id);
    }

    /**
     * > This function will return all the rows in the table where the restriction column is equal to 0
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeNotRestricted($query)
    {
        return $query->where('restriction', 0);
    }

    /**
     * > This function will return a query where the role is not equal to the value passed in
     * 
     * @param query The query builder instance.
     * @param value The value to compare against.
     * 
     * @return A query builder object
     */
    public function scopeWhereNot($query, $value)
    {
        return $query->where('role', '!=' ,$value);
    }

    /**
     * > This function returns a relationship between the User model and the Identity model
     * 
     * @return The user's identity.
     */
    public function identity()
    {
        return $this->hasOne(Identity::class, 'user_id', 'id');
    }

    // ENDS
}

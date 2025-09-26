<?php

namespace App\Models;

use Auth;
use App\Models\DepartmentAgent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * relation with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The function returns a hasMany relationship between the current object and the DepartmentAgent
     * model.
     * 
     * @return HasMany a HasMany relationship.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(DepartmentAgent::class, 'agent_id', 'id');
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

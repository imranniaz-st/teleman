<?php

namespace App\Models;

use App\Models\GroupContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsSchedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class PackageSupportedCountry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['twilio_call_cost'];

    // relqation with TwilioCallCost
    public function twilio_call_cost()
    {
        return $this->hasOne(TwilioCallCost::class, 'id', 'twilio_call_costs_id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\TwilioSmsCost;

class TwilioCallCost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
     * Twilio SMS Cost
     */
    public function twilio_sms_cost()
    {
        return $this->hasOne(TwilioSmsCost::class, 'twilio_call_cost_id', 'id');
    }
    // ENDS
}

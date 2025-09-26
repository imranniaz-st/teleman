<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // relation with packages
    public function package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }

    // relation with user
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // relation with item limit counts
    public function item_limit_count()
    {
        return $this->hasOne(ItemLimitCount::class, 'subscription_id', 'id');
    }

    // relation with item limit counts
    public function payment_history()
    {
        return $this->hasMany(PaymentHistory::class, 'subscription_id', 'id');
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

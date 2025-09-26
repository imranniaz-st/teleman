<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }
}

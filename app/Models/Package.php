<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Package extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // relation with PackageSupportedCountry
    public function supported_countries()
    {
        return $this->hasMany(PackageSupportedCountry::class, 'package_id', 'id');
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

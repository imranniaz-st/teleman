<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    /**
     * Return all the movies that have been released.
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeReleased($query)
    {
        return $query->where('released', 1);
    }

    /**
     * Return all users where the confirmed column is equal to 1.
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', 1);
    }

    /**
     * > This function will return all the users that have not confirmed their email address
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeNotConfirmed($query)
    {
        return $query->where('confirmed', 0);
    }

    /**
     * It returns the query where the renew column is equal to 1.
     * 
     * @param query The query builder instance.
     * 
     * @return A query builder object.
     */
    public function scopeRenew($query)
    {
        return $query->where('renew', 1);
    }

    /**
     * It returns the provider that has the same phone number as the user.
     * 
     * @return A single Provider object
     */
    public function provider()
    {
        return $this->hasOne(Provider::class, 'phone', 'phone');
    }
}

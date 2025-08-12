<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the states for the country.
     */
    public function states()
    {
        return $this->hasMany(State::class);
    }

    /**
     * Get the cities for the country through states.
     */
    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class);
    }
}

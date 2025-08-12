<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
    protected $table = 'client_types';

    protected $fillable = [
        'name',
        'code',
        'status',
        'discount_rates',
        'remarks',
        'created_by',
        'updated_by',
    ];


}

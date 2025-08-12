<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name',
        'website_url',
        'registered_office_address',
        'registered_office_address2',
        'email_id',
        'office_address',
        'office_address2',
        'phone_number',
        'whatsapp',
        'customer_care',
        'created_by',
        'updated_by',
        'gst_number',
        'pan_number',
        'timezone',
        'currency',
        'prior_hours_preferred_time',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    // Dynamically set $fillable from JSON
    protected $fillable = [
        'name',
        'class_name',
        'slug',
        'icon',
        'parent_id',
        'description',
        'add_form',
        'edit_form',
        'view_list',
        'status',
        'is_published',
        'is_left_menu',
        'is_accordion',
        'sort_order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}

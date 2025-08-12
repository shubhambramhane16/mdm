<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    protected $table = 'bank_details';

    protected $fillable = [
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'account_holder_name',
        'bank_address',
    ];

    public $timestamps = true;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}

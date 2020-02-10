<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Return the amount in float
     * @param string $value
     * @return float
     */
    public function getAmountAttribute($value) 
    {
        return minorToFloat($value);
    }
}

<?php

namespace App\Services\Checkout;

use App\Models\ServiceResponse;

class CheckoutService 
{
    public function generateToken(\Illuminate\Http\Request $request) : ServiceResponse 
    {
        $result = new ServiceResponse('Fail', 'Internal Server Error.', 500, []);
        
        return $result;
    }
}
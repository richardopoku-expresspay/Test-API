<?php

namespace App\Repositories\Checkout;

interface CheckoutInterface
{
    public function createTransaction(\Illuminate\Http\Request $request) : \App\Models\Transaction;
}
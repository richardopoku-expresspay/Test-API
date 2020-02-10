<?php

namespace App\Repositories\Checkout;

use App\Models\ServiceResponse;
use App\Models\Transaction;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class CheckoutRepository implements CheckoutInterface 
{
    public function createTransaction(\Illuminate\Http\Request $request) : \App\Models\Transaction
    {
        DB::beginTransaction();

        try {
            $transaction = new Transaction();
            $transaction->amount = floatToMinor(minorToFloat($request->amount));
            $transaction->order_id = $request->order_id;
            $transaction->currency = $request->currency;
            $transaction->details = ['customer' => $request->customer, 'description' => $request->description, 'redirect_url' => $request->redirect_url, 'callback_url' => $request->callback_url];
            $transaction->save();
        } catch(\Exception $e) {
            $service = new ServiceResponse('Fail', 'Internal Server Error', 500, ['reason' => $e->getMessage()]);
            throw new HttpResponseException(response()->json($service->toArray(), $service->toArray()['error_code']));

            DB::rollBack();
        }

        DB::commit();

        return $transaction;
    }

    public function updateTransaction(\App\Models\Transaction $transaction, array $details) : \App\Models\Transaction
    {
        DB::beginTransaction();

        try {
            foreach($details as $key => $value) {
                $transaction->$key = $value;
            }
            $transaction->save();
        } catch(\Exception $e) {
            $service = new ServiceResponse('Fail', 'Internal Server Error', 500, ['reason' => $e->getMessage()]);
            throw new HttpResponseException(response()->json($service->toArray(), $service->toArray()['error_code']));

            DB::rollBack();
        }

        DB::commit();

        return $transaction->fresh();
    }
}
<?php

namespace App\Services\Checkout;

use App\Models\ServiceResponse;
use App\Repositories\Checkout\CheckoutInterface;

class CheckoutService 
{

    /** @var CheckoutInterface $checkout */
    private $checkout;

    public function __construct(CheckoutInterface $interface)
    {
        $this->checkout = $interface;
    }

    public function generateToken(\Illuminate\Http\Request $request) : ServiceResponse 
    {
        $result = new ServiceResponse('Fail', 'Internal Server Error.', 500, []);

        $transaction = $this->checkout->createTransaction($request);

        //send the payload to expresspay merchant api
        $url = config('expresspay.checkout.submit_url');
        $response = $this->sendPayloadToExpressPay($transaction, $url);

        $response = $this->handleTokenResponse($response, $transaction);

        if (array_key_exists('status', $response) && array_key_exists('error_code', $response) && array_key_exists('message', $response)) {
            if (!array_key_exists('data', $response)) {
                $result = new ServiceResponse($response['status'], $response['message'], $response['error_code']);
            } else {
                $result = new ServiceResponse($response['status'], $response['message'], $response['error_code'], $response['data']);
            }
        }
        
        return $result;
    }

    /**
     * Send the transaction payload to expressPay to generate the token
     * @param \App\Models\Transaction $transaction
     * @param string $url
     * @return array
     */
    protected function sendPayloadToExpressPay(\App\Models\Transaction $transaction, $url) : array
    {
        $details = $transaction->details;
        $customer = $details['customer'];
        $payload = [
            'merchant-id' => config('expresspay.merchant_id'),
            'api-key' => config('expresspay.api_key'),
            'firstname' => $customer['first_name'],
            'lastname' => $customer['last_name'],
            'email' => $customer['email'],
            'phonenumber' => $customer['phone_number'],
            'username' => $customer['username'],
            'accountnumber' => $customer['account_number'],
            'currency' => $transaction->currency,
            'amount' => $transaction->amount,
            'order-id' => $transaction->order_id,
            'redirect-url' => $details['redirect_url'],
            'post-url' => $details['callback_url'],
        ];

        //the optional fields
        $fields = ['description' => 'order-desc', 'image_url' => 'order-img-url'];

        foreach($fields as $field => $name) {
            if (array_key_exists($field, $details) && !is_null($details[$field])) {
                $payload[$name] = $details[$field];
            }
        }

        $response = sendHttpRequest($url, 'POST', $payload, 'form_params');

        return $response;
    }

    /**
     * Handle the response from expressPay
     * @param array $data
     * @param \App\Models\Transaction $transaction
     * @return array
     */
    protected function handleTokenResponse($data, \App\Models\Transaction $transaction) : array
    {
        if (array_key_exists('status', $data) && array_key_exists('message', $data) && array_key_exists('token', $data)) {
            //if not successful
            if (!in_array($data['status'],[1, '1'])) {
                return errorResponse($data['message'], 500, $data);
            }
            //update the transaction
            $details = $transaction->details;
            $details['response'] = $data;
            $this->checkout->updateTransaction($transaction, ['details' => $details]);

            //create the payload with the token and the redirect url for the user
            $code = [
                'token' => $data['token'],
                'redirect_url' => config('expresspay.checkout.redirect_url') . '?token=' . $data['token']
            ];

            return successResponse('Token generation successful.', 200, $code);
        } else {
            return errorResponse('Failed generating the redirect token', 500, $data);
        }
    }
}
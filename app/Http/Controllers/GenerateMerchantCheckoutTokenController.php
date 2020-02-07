<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Checkout\CheckoutService;
use App\Http\Requests\MerchantTokenFormRequest;

class GenerateMerchantCheckoutTokenController extends Controller
{
    /**
     * @var CheckoutService $checkoutService
     */
    private $checkoutService;

    /**
     * Constructor
     * @param CheckoutService $checkoutService
     */
    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(MerchantTokenFormRequest $request)
    {
        $result = $this->checkoutService->generateToken($request);

        return response()->json($result->toArray(), $result->toArray()['error_code']);
    }
}

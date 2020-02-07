<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Http\Redirector;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class FormRequest extends HttpRequest implements ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    public function validate()
    {
        if (false === $this->authorize()) {
            throw new UnauthorizedException();
        }

        $validator = app('validator')->make($this->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            $result = [ 'message' => 'The given data was invalid.', 'errors' => $validator->errors()->toArray()];
            throw new HttpResponseException($this->response($result));
        }
    }

    public function resolveUser()
    {
        if (method_exists($this, 'setUserResolver')) {
            $this->setUserResolver(function () {
                return Auth::user();
            });
        }
    }


    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }

    protected function authorize()
    {
        return true;
    }

    abstract protected function rules ();

    protected function messages ()
    {
        return [];
    }
}
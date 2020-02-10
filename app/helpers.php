<?php

use Zttp\Zttp;

if (!function_exists('baseResponse')) {
    /**
     * Base response to return
     * @param string $status
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return array
     */
    function baseResponse($status, $message, $error_code, $data=[]) : array {
        $result = [
            'status' => ucfirst($status),
            'message' => $message,
            'error_code' => $error_code,
        ];

        if (is_array($data) && !empty($data)) {
            $result['data'] = $data;
        }

        return $result;
    }
}

if (!function_exists('successResponse')) {
    /**
     * Success response to return
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return array
     */
    function successResponse($message, $error_code=200, $data=[]) : array {
        return baseResponse('Success', $message, $error_code, $data);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * Error or Failed response
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return array
     */
    function errorResponse($message, $error_code=400, $data=[]) : array {
        return baseResponse('Declined', $message, $error_code, $data);
    }
}

if (!function_exists('sendHttpRequest')) {
    /**
     * Make an Http Request with the given params
     * @param string $url
     * @param string $method
     * @param array $body
     * @param string $body_type
     * @param array $headers
     * @param boolean $verify_ssl
     * @return array
     */
    function sendHttpRequest($url, $method, $body=[], $body_type='json', $headers=[], $verify_ssl=false) {
        $head = [];
        $content = [];

        if (!empty($headers)) {
            $head = $headers;
        }

        //check for data_types
        if ($body_type === 'json') {
            if (!array_key_exists('content-type', $head)) {
                $header['content-type'] = 'application/json';
                $header['accept'] = 'application/json';
            }
        } elseif ($body_type === 'form_params') {
            if (!array_key_exists('content-type', $head)) {
                $header['content-type'] = 'application/x-www-form-urlencoded';
            }
        }

        if(!empty($body)) {
            $content = $body;
        }

        if ($method === 'post') {
            //check if headers not empty
            if(empty($head) && $body_type == 'form_params') {
                $response = Zttp::withHeaders($head)->asFormParams()->post($url, $content);
            } elseif (empty($head) && $body_type == 'json') {
                $response = Zttp::post($url, $content);
            } elseif(!empty($head) && $body_type == 'json') {
                $response = Zttp::withHeaders($head)->post($url, $content);
            } elseif(!empty($head) && $body_type == 'form_params') {
                $response = Zttp::asFormParams()->withHeaders($head)->post($url, $content);
            } else {
                $response = Zttp::withHeaders($head)->post($url, $content);
            }
        } elseif ($method === 'put') {
            //check if headers not empty
            if (empty($head) && $body_type == 'form_params') {
                $response = Zttp::asFormParams()->put($url, $content);
            } elseif(empty($head) && $body_type == 'json') {
                $response = Zttp::put($url, $content);
            } elseif(!empty($head) && $body_type == 'form_params') {
                $response = Zttp::withHeaders($head)->put($url, $content);
            } elseif(!empty($head) && $body_type == 'json') {
                $response = Zttp::withHeaders($head)->put($url, $content);
            } else {
                $response = Zttp::withHeaders($head)->put($url, $content);
            }
        } elseif ($method === 'patch') {
            //check if headers not empty
            if (empty($head) && $body_type == 'form_params') {
                $response = Zttp::asFormParams()->patch($url, $content);
            } elseif(empty($head) && $body_type == 'json') {
                $response = Zttp::patch($url, $content);
            } elseif(!empty($head) && $body_type == 'form_params') {
                $response = Zttp::withHeaders($head)->patch($url, $content);
            } elseif(!empty($head) && $body_type == 'json') {
                $response = Zttp::withHeaders($head)->patch($url, $content);
            } else {
                $response = Zttp::withHeaders($head)->patch($url, $content);
            }
        } elseif ($method === 'delete') {
            if (empty($head) && $body_type == 'form_params') {
                $response = Zttp::asFormParams()->delete($url, $content);
            } elseif(empty($head) && $body_type == 'json') {
                $response = Zttp::delete($url, $content);
            } elseif (!empty($head) && $body_type == 'form_params') {
                $response = Zttp::withHeaders($head)->asFormParams()->delete($url, $content);
            } elseif (!empty($head) && $body_type == 'json') {
                $response = Zttp::withHeaders($head)->delete($url, $content);
            } else {
                $response = Zttp::withHeaders($head)->delete($url, $content);
            }
        } else {
            //assume it is a get request
            if (empty($head) && $body_type == 'form_params') {
                $response = Zttp::asFormParams()->get($url, $content);
            } elseif (!empty($head) && $body_type == 'form_params') {
                $response = Zttp::withHeaders($head)->asFormParams()->get($url, $content);
            } else {
                $response = Zttp::withHeaders($head)->get($url, $content);
            }
        }

        return $response->json();
    }
}

if (!function_exists('getHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function getHttpRequest($url, $body=[], $headers=[], $body_type='form_params', $verify_ssl=false) {
        return sendHttpRequest($url, 'get', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('postHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function postHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        return sendHttpRequest($url, 'post', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('putHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function putHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        return sendHttpRequest($url, 'put', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('patchHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function patchHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        return sendHttpRequest($url, 'patch', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('deleteHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function deleteHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        return sendHttpRequest($url, 'delete', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('minorToFloat')) {
    /**
     * @param string|int $amount
     * @return float
     */
    function minorToFloat($amount) : float {
        return ((float) $amount / 100.0 );
    }
}

if (!function_exists('floatToMinor')) {
    /**
     * @param float|int $amount
     * @return string
     */
    function floatToMinor($amount) : string {
        //multiply it by 100 first
        //based on the length, append
        $val = ((string) $amount * 100);
        //pad it with zeros
        $iter = 12 - strlen($val);
        return str_repeat('0', $iter) . $val;; 
    }
}
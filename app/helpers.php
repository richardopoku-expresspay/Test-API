<?php

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
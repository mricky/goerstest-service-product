<?php
namespace App\Helpers;

class Formater
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'success' => true,
        'message' => '',
        'data' => [],
    ];
    /**
     * Give success response.
     */
    public static function success($data = [], $message = '')
    {
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        return response()->json(self::$response, 200);
    }
    /**
     * Give error response.
     */
    public static function error($message = '', $code = 400, $data = [])
    {
        self::$response['success'] = false;
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        return response()->json(self::$response, $code);
    }
}
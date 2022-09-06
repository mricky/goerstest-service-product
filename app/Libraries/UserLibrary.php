<?php
namespace App\Libraries;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
class UserLibrary
{
    protected static $response = [
        'success' => false,
        'message' => null,
        'data' => null,
    ];
    protected static $headers = [
        'Agent' => 'application/json',
        'Content-Type' => 'application/json',
       // 'Authorization' => 'Bearer token', this handle on security spec
    ];
    public static function response($method, $url, $request = null)
    {
        $http = new Client([
            'base_uri' =>
                config('servicesGoers.user.domain') .
                'api/' .
                config('servicesGoers.user.version'),
            'headers' => self::$headers,
            'body' => json_encode($request),
        ]);
        try {
            $response = $http->request($method, $url);
            $json = json_decode($response->getBody());
            self::$response['success'] = true;
            self::$response['data'] = optional($json)->data;
        } catch (BadResponseException $e) {
            self::$response['message'] = 'BadResponseException! ' . $e->getMessage();
        } catch (ClientException $e) {
            self::$response['message'] = 'ClientException! ' . $e->getMessage();
        } catch (ServerException $e) {
            self::$response['message'] = 'ServerException! ' . $e->getMessage();
        }
        return (object) self::$response;
    }
}
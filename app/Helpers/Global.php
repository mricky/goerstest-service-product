<?php

if (!function_exists('getMidtransSnapUrl')) {
    function getMidtransSnapUrl($param){
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_PRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_3DS');

        $snapUrl = \Midtrans\Snap::createTransaction($param)->redirect_url;
        return $snapUrl;
    }
}
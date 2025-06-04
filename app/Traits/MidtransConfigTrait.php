<?php

namespace App\Traits;

trait MidtransConfigTrait
{
    protected function configureMidtrans()
    {
        // Validate environment variables first
        $serverKey = config('midtrans.serverKey');
        if (empty($serverKey)) {
            throw new \Exception('Midtrans server key is not configured');
        }

        // Configure Midtrans with aggressive SSL bypass for development
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        // Set comprehensive CURL options to bypass SSL issues
        \Midtrans\Config::$curlOptions = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => 'Travesia/1.0 (Laravel)',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        );

        // Additional debugging for development
        if (!config('midtrans.isProduction', false)) {
            \Log::info('Midtrans configured for sandbox mode with SSL bypass');
        }
    }
} 
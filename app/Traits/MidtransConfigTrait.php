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

        // Validate key consistency with environment
        $isProduction = config('midtrans.isProduction', false);
        $isSandboxKey = str_starts_with($serverKey, 'SB-');
        
        if ($isProduction && $isSandboxKey) {
            throw new \Exception('Configuration error: You are using sandbox keys (SB-) but MIDTRANS_IS_PRODUCTION is set to true. Please set MIDTRANS_IS_PRODUCTION=false for sandbox keys, or use production keys without SB- prefix.');
        }
        
        if (!$isProduction && !$isSandboxKey) {
            throw new \Exception('Configuration error: You are using production keys but MIDTRANS_IS_PRODUCTION is set to false. Please set MIDTRANS_IS_PRODUCTION=true for production keys, or use sandbox keys with SB- prefix.');
        }

        // Configure Midtrans with proper settings
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = $isProduction;
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized', true);
        \Midtrans\Config::$is3ds = config('midtrans.is3ds', true);
        
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

        // Log configuration for debugging
        \Log::info('Midtrans Configuration:', [
            'isProduction' => $isProduction,
            'serverKey' => substr($serverKey, 0, 20) . '...',
            'environment' => $isSandboxKey ? 'sandbox' : 'production'
        ]);
    }
} 
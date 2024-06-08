<?php

namespace App\Services;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalService
{
    private $apiContext;

    public function __construct()
    {
        // Set the PayPal API Context/Credentials
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
        );

        // Configure the ApiContext with the settings defined in services.php
        $this->apiContext->setConfig([
            'mode' => config('services.paypal.settings.mode'),
            'http.ConnectionTimeOut' => config('services.paypal.settings.http.ConnectionTimeOut'),
            'log.LogEnabled' => config('services.paypal.settings.log.LogEnabled'),
            'log.FileName' => config('services.paypal.settings.log.FileName'),
            'log.LogLevel' => config('services.paypal.settings.log.LogLevel')
        ]);
    }

    public function getApiContext()
    {
        return $this->apiContext;
    }

    // Add more methods as needed for creating payments, handling responses, etc.
}

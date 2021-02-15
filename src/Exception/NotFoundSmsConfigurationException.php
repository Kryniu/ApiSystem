<?php


namespace App\Exception;


use Exception;

class NotFoundSmsConfigurationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not found sms configuration in env SMSAPI_AUTH_TOKEN or SMSAPI_FROM');
    }
}
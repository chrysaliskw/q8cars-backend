<?php

namespace App\Exceptions;

use Exception;

class FuelTtypeAndTransmissionException extends Exception
{
    // Optionally, you can customize the constructor and pass additional data
    public function __construct($message , $code = 400)
    {
        parent::__construct($message, $code);
    }
}

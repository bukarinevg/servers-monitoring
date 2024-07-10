<?php

namespace app\source\exceptions;

use Exception;

class NotFoundException extends Exception
{
    /**
     * Constructor
     * 
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(string $message = "Not Found", int $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
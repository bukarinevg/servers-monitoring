<?php 

namespace app\source\exceptions;

use Exception;


class BadRequestException extends Exception
{
    /**
     * Constructor
     * 
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(string $message = "Bad Request", int $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
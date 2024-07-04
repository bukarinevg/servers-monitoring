<?php

namespace app\source\http;

use app\source\http\ResponseHandlers\ResponseHandlerInterface;

class ResponseStrategy
{
    private ResponseHandlerInterface $responseHandler;

    public function __construct(ResponseHandlerInterface $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }

    public function handle(): void
    {
        $this->responseHandler->handle();
    }
}
<?php

namespace app\source\http;

use app\source\http\ResponseError;
use app\source\http\ResponseHandlers\GetHandler;
use app\source\http\ResponseHandlers\PostHandler;
use app\source\http\ResponseHandlers\PutHandler;
use app\source\http\ResponseHandlers\DeleteHandler;



class ResponseHandlerFactory {
    public static function createHandler($method, $result = null) {
        switch ($method) {
            case 'GET':
                return new GetHandler();
            case 'POST':
                return new PostHandler($result);
            case 'PUT':
                return new PutHandler();
            case 'DELETE':
                return new DeleteHandler();
            default:
                ResponseError::setResponse('405 Method Not Allowed', 'Method not allowed');
        }
    }
}
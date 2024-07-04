<?php

namespace app\source\http\ResponseHandlers;

use app\source\http\ResponseHandlers\ResponseHandlerInterface;

class PostHandler implements ResponseHandlerInterface {
    private $result;

    public function __construct($result) {
        $this->result = $result;
    }

    public function handle() : void {
        http_response_code(201);
        $path = explode('/', $_SERVER['REQUEST_URI']);
        $path = array_slice($path, 0, count($path) - 2);
        $location = implode('/', $path) . '/' . $this->result;
        header("Location: $location");
    }
}
<?php

namespace app\source\http\ResponseHandlers;

class PutHandler implements ResponseHandlerInterface {
  

    public function handle() : void {
        http_response_code(200);
    }
}
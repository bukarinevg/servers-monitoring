<?php

namespace app\source\http\ResponseHandlers;


class DeleteHandler implements ResponseHandlerInterface
{
    public function handle(): void
    {
        http_response_code(204);
    }
}
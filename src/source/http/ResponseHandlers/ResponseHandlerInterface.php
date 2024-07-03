<?php
namespace app\source\http\ResponseHandlers;

interface ResponseHandlerInterface {
    public function handle(): void;
}
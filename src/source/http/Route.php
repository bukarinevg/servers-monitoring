<?php

namespace app\source\http;

use app\source\http\Url;
use League\Container\Exception\NotFoundException;


/**
 * Class UrlRouting
 * 
 * This class extends the Url class and is responsible for handling URL routing.
 */

class Route{
    public function __construct(public string $controller, public string $method, public string|null $param = null) {
    }

    public function validate() : bool {
        if(!class_exists($this->controller)){
            echo $this->controller;
            throw new NotFoundException('Controller not found');
        }
        if(!method_exists($this->controller, $this->method)){
            echo $this->method;
            throw new NotFoundException('Method not found');
        }
        if($this->isParam() && $this->param === null){
            throw new NotFoundException('Parameter not found');
        }

        return true;
    }

    private function isParam() : bool {
        $reflector = new \ReflectionMethod($this->controller, $this->method);
        $params = $reflector->getParameters();
        return count($params) > 0;
    }

} 
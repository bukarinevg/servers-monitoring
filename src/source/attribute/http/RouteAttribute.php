<?php

namespace app\source\attribute\http;

use app\source\enums\ParamTypeEnum;
use Attribute;

/**
 * Class Route Attribute
 * 
 * This class is an attribute that defines a route.
 */
#[Attribute]
class RouteAttribute
{
    /**
     * The route param.
     *
     * @var string
     */
    public string $param;

    /**
     * The route method.
     *
     * @var string
     */
    public string $method;

    public function __construct(string $param, string $method)
    {
        $this->param = $param;
        $this->method = $method;
    }

    public function validate() : bool
    {
        if ( $this->method == $_SERVER['REQUEST_METHOD']) {

            if($this->param == null) {
                return True;
            }

            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $lastPathValue = basename($path);

            if (is_numeric($lastPathValue) && $this->param == ParamTypeEnum::INT->value) {
                return True;
            }

            if(is_string($lastPathValue) && $this->param == ParamTypeEnum::STRING->value) {
                return True;
            }

            if(is_bool($lastPathValue) && $this->param == ParamTypeEnum::BOOL->value) {
                return True;
            }

            if(is_float($lastPathValue) && $this->param == ParamTypeEnum::FLOAT->value) {
                return True;
            }

            throw new \app\source\exceptions\BadRequestException('The param is not valid');

        }
        return False;
    }

}
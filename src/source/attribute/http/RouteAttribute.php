<?php

namespace app\source\attribute\http;
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
     * The route path.
     *
     * @var string
     */
    public string $path;

    /**
     * The route method.
     *
     * @var string
     */
    public string $method;

    /**
     * RouteAttribute constructor.
     *
     * @param string $path The route path.
     * @param string $method The route method.
     */
    public function __construct(string $path, string $method)
    {
        $this->path = $path;
        $this->method = $method;
    }

    public function validate() : bool
    {
        if ( $this->method == $_SERVER['REQUEST_METHOD']) {
            return True;
        }
        return False;
    }

}
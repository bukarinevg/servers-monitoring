<?php
namespace app\source\http;

use app\source\http\Url;
use League\Container\Exception\NotFoundException;

/**
 * Class UrlRouting
 * 
 * This class extends the Url class and is responsible for handling URL routing.
 */

class UrlRouting  extends Url{
    /**
     * @var string CONTROLLER_NAMESPACE The namespace for the controllers.
     */
    const CONTROLLER_NAMESPACE = 'app\controllers\\';

    /**
     * Constructor for the UrlRouting class.
     */
    public function __construct() {
        parent::__construct($_SERVER);     
    }

    /**
     * Gets the controller and method from the URL.
     *
     * @return array An array containing the controller and method.
     */
    public function getControllerFullAddress(): array {
        $url = $this->getPath();
        
        if(strpos($url, '-') ){
            $trimmedUrl = ltrim($url, '-');
            $parts = explode('-', $trimmedUrl);
            $parts = array_map(function($part) {
                return ucfirst($part);
            }, $parts);
            $url = implode('', $parts);
        }

        $url = rtrim($url, '/');
        $url = explode('/', ltrim( $url, '/'));

        $length = count($url);
        if($length == 0){
            throw new NotFoundException('No controller found');
        }
        elseif($length == 1){
           throw new NotFoundException('No method found');
        }

        $path = '';

        for($i = 0; $i < $length -2 ; $i++){
            $path .= ucfirst($url[$i]) . '\\';  
        }

        $controller =ucfirst($url[$length - 2]);
        $controller = $this->getControllerName($path.$controller);
        $method = ucfirst($url[$length - 1]);
        $method =  $this->getMethodName($method);

        if(class_exists($controller) && method_exists($controller, $method)){
            $reflector = new \ReflectionMethod($controller, $method);
            $params = $reflector->getParameters();
            if(count($params) == 0){
                // echo 'contollers exists and method exists no params required'; 
                return [ 
                    'controller' => $controller, 
                    'method'     => $method,
                    'param'     => null
                ];
            }
            
        }

        $path = '';

        if($length < 3){
            throw new NotFoundException('Wrong URL format');
        }

        for($i = 0; $i < $length -3 ; $i++){
            $path .= ucfirst($url[$i]) . '\\';  
        }
        
        $controller =ucfirst($url[$length - 3]);
        $controller = $this->getControllerName($path.$controller);
        $method = ucfirst($url[$length - 2]);
        $method =  $this->getMethodName($method);
        $param = $url[$length - 1];


        if(class_exists($controller) && method_exists($controller, $method)){
            $reflector = new \ReflectionMethod($controller, $method);
            $params = $reflector->getParameters();
            if(count($params) == 1){
                // echo 'contollers exists and method exists params required'; 
                return [ 
                    'controller' => $controller, 
                    'method'     => $method,
                    'param'     => $param
                ];
            }
            else{
                throw new NotFoundException('Parameter not found');
            }
        }

        throw new NotFoundException('Controller or method not found');



    }
    /**
     * Gets the controller name from the URL.
     *
     * @param string $url The URL.
     * @return string The controller name.
     */
    private function getControllerName($url = null): string {
        return self::CONTROLLER_NAMESPACE . ( $url ). 'Controller';
    }

    /**
     * Gets the method name from the URL.
     *
     * @param string $url The URL.
     * @return string The method name.
     */
    private function getMethodName($url = null): string {
        return 'action' . ( ucfirst($url) );
    }

    private function checkController($controller, $method): bool {
        return class_exists($controller) && method_exists($controller, $method);
    }
}

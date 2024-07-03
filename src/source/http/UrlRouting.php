<?php
namespace app\source\http;

use app\source\http\Url;
use League\Container\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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
     * @return Route An array containing the controller and method.
     */
    public function getRouteDetails(): Route {
        $path = $this->getPath();
        $urlNamesArray = $this->parsePathToNamesArray($path);  
        $routeElementsWithoutParam = 2;
        $routeElementsWithParam = 3;
        
        $route = $this->findRoute($urlNamesArray, $routeElementsWithoutParam);
        if($route){
            return $route;
        }
        if($route = $this->findRoute($urlNamesArray, $routeElementsWithParam)){
            return $route;
        }

        throw new NotFoundException('Controller or method not found');
    }

    private function findRoute(array $urlNamesArray, int $routeElements ) : Route | bool{
        if(count($urlNamesArray) < $routeElements){
            throw new NotFoundException('Controller or method not found');
        }

        $nameSpaceElementsArray = array_slice($urlNamesArray, 0, $routeElements);
        $nameSpace = $this->buildNameSpace($nameSpaceElementsArray);
        
        $routeArray = array_slice($urlNamesArray, $routeElements);

        $controller = $this->getControllerName($routeArray[0], $nameSpace);
        $method = $this->getMethodName($routeArray[1]);
        $param = isset($routeArray[2]) ? $routeArray[2] : null;

        $route = new Route($controller, $method, $param);
        
        if($route->validate()){
             return $route;
        }else{
            return false;
        }
        
    }

    /**
     * Parses the URL path.
     *
     * @param string $url The URL.
     * @return array The parsed URL.
     */
    private function parsePathToNamesArray(string $url): array {
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
        return $url;
    }

    private function getControllerName(string $controllerName, string $nameSpace): string {
        $controllerName = ucfirst($controllerName);
        $controllerName = self::CONTROLLER_NAMESPACE . $nameSpace . $controllerName . 'Controller';
        return $controllerName;
    }

    private function getMethodName($url = null): string {
        return 'action' . ( ucfirst($url) );
    }

    private function buildNameSpace(array $urlNamesArray): string {
        $nameSpace = '';
        for($i = 0; $i <  count($urlNamesArray); $i++){
            $nameSpace .= ucfirst($urlNamesArray[$i]) . '\\';  
        }
        return $nameSpace;

    }
}

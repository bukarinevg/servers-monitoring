<?php
namespace app\source;

use app\source\attribute\http\RouteValidationResource;
use app\source\db\DataBase;
use app\source\http\RequestHandler;
use app\source\http\UrlRouting;
use app\source\SingletonTrait;

readonly class App
{
    use SingletonTrait;

    /**
     * @var mixed $request The request object.
     */
    private RequestHandler $request;
    


    public function __construct(#[\SensitiveParameter] private  array $config){
        DataBase::getInstance($this->config['components']['db']);
    }


    public function run()
    {
        $this->setRequest(new RequestHandler());
        $this->callController( (new UrlRouting())->getController() );        
    }

    /**
     * Calls the controller and method from the URL.
     *
     * @param array $route An array containing the controller and method.
     */
    public function callController(array $route) 
    {
        print_r($route);
        try {
            $controller =  $route['controller'];
            $method = $route['method'];
            $param = $route['param'];
            
            if (!class_exists($controller)) {
                throw new \Exception("Controller $controller does not exist");
            }
            $controllerInstance = new $controller($this);
            
            if (!is_callable([$controllerInstance, $method])) {
                throw new \Exception("Method $method is not callable in controller $controller");
            }

            RouteValidationResource::validateRoute(
                class: $controllerInstance::class, 
                method: $method
            );

            if($param){
                $controllerInstance->$method($param);
            }
            else{
                $controllerInstance->$method();
            }
    

            
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Get the value of request
     */ 
    public function getRequest(): RequestHandler
    {
        return $this->request;
    }

    /**
     * Set the value of request
     *
     * Handles the request.
     * @return  void
     */ 
    public function setRequest(RequestHandler $requestHandler): void
    {
        $this->request = $requestHandler;

    }
}

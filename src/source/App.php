<?php
namespace app\source;


use app\source\http\RequestHandler;
use app\source\http\UrlRouting;


readonly class App
{
    /**
     * @var mixed $request The request object.
     */
    private RequestHandler $request;
    


    function __construct(#[\SensitiveParameter] private  array $config){}


    public function run()
    {
        $this->setRequest(new RequestHandler());
        $this->callController((new UrlRouting())->getController() );        
    }

    /**
     * Calls the controller and method from the URL.
     *
     * @param array $route An array containing the controller and method.
     */
    public function callController(array $route) 
    {
        try {
            $controller =  $route['controller'];
            $method = $route['method'];
            
            if (!class_exists($controller)) {
                throw new \Exception("Controller $controller does not exist");
            }
            $controllerInstance = new $controller($this);
    
            if (!method_exists($controllerInstance, $method)) {
                throw new \Exception("Method $method does not exist in controller $controller");
            }
    
            $controllerInstance->$method();
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

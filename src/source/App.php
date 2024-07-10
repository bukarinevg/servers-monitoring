<?php
namespace app\source;

use app\source\attribute\http\RouteValidationResource;
use app\source\db\DataBase;
use app\source\http\RequestHandler;
use app\source\http\UrlRouting;
use app\source\http\ResponseError;
use app\source\http\ResponseHandlerFactory;
use app\source\http\ResponseStrategy;
use app\source\SingletonTrait;
use BadMethodCallException;
use League\Container\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use PDOException;


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
        header('Content-Type: application/json');
        ob_start();
        try {
            $this->setRequest(new RequestHandler());
            $requestMethod = $this->request->getRequest()->getMethod();

            $route = (new UrlRouting())->getRouteDetails();
            
            $routeAction = $this->getRouteAction( $route->controller,$route->method );
            $result = $routeAction($route->param);    
            
            $responseHandler  = ResponseHandlerFactory::createHandler($requestMethod, $result);
            $handlingStrategy = new ResponseStrategy($responseHandler);
            $handlingStrategy->handle();          
    
        } catch (NotFoundException $th) {
            ResponseError::setResponse('404 Not Found', $th->getMessage());
        }
        catch (BadMethodCallException $th) {
            ResponseError::setResponse('405 Method Not Allowed', $th->getMessage());
        }
        catch(PDOException $th){
            ResponseError::setResponse('500 Internal Server ResponseError', $th->getMessage());
        }
        catch(BadRequestException $th){
            ResponseError::setResponse('400 Bad Request', $th->getMessage());
        }
        catch(\Throwable $th){
            ResponseError::setResponse('500 Internal Server ResponseError', $th->getMessage());
        }
        ob_end_flush();
    }

    /**
     * Calls the controller and method from the URL.
     *
     * @param array $route An array containing the controller and method.
     */
    private function getRouteAction(string $controller, string $method) : callable
    {

        $controllerInstance = new $controller($this);
        
        RouteValidationResource::validateRoute(
            class: $controllerInstance::class, 
            method: $method
        );

        return function($param = null) use ($controllerInstance, $method) {
            return $controllerInstance->$method($param);
        };
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

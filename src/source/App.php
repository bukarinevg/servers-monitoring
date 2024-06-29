<?php
namespace app\source;

use app\source\attribute\http\RouteValidationResource;
use app\source\db\DataBase;
use app\source\http\RequestHandler;
use app\source\http\UrlRouting;
use app\source\http\Error;
use app\source\SingletonTrait;
use BadMethodCallException;
use Exception;
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
        try {
            header('Content-Type: application/json');
            $this->setRequest(new RequestHandler());

            $controllerDetails = (new UrlRouting())->getControllerFullAddress();
            
            $controllerAction = $this->getControllerAction( 
                $controllerDetails['controller'], 
                $controllerDetails['method'] 
            ); 


            ob_start();

            $result = $controllerAction($controllerDetails['param']);


            switch ($this->request->getRequest()->getMethod()) {
                case 'GET':
                    http_response_code(200);
                    break;
                case 'POST':
                    http_response_code(201);
                    $path = explode('/', $_SERVER['REQUEST_URI']);
                    array_slice($path, 0, count($path) - 2);
                    $location = implode('/', $path);
                    $location = $location . '/' . $result;
                    
                    header("Location:  $location");
                    break;
                case 'PUT':
                    http_response_code(200);
                    break;
                case 'DELETE':
                    http_response_code(204);
                    break;
                default:
                    Error::setResponse('405 Method Not Allowed', 'Method not allowed');
                    break;
            }

            ob_end_flush();
            
    
        } catch (NotFoundException $th) {
            Error::setResponse('404 Not Found', $th->getMessage());
        }
        catch (BadMethodCallException $th) {
            Error::setResponse('405 Method Not Allowed', $th->getMessage());
        }
        catch(PDOException $th){
            Error::setResponse('500 Internal Server Error', $th->getMessage());
            exit();
        }
        catch(BadRequestException $th){
            Error::setResponse('400 Bad Request', $th->getMessage());
            exit();
        }
        catch(\Throwable $th){
            Error::setResponse('500 Internal Server Error', $th->getMessage());
            exit();
        }
        finally{
            exit();
        }
      

    }

    /**
     * Calls the controller and method from the URL.
     *
     * @param array $route An array containing the controller and method.
     */
    public function getControllerAction(string $controller, string $method) : callable
    {

        $controllerInstance = new $controller($this);
        
        RouteValidationResource::validateRoute(
            class: $controllerInstance::class, 
            method: $method
        );


        return function($param = null) use ($controllerInstance, $method) {
            return $controllerInstance->$method($param);
        };;

            
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

<?php
namespace app\source\http;

use app\source\http\Url;

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
    public function getController(): array {
        $url = $this->getPath();
        $url = explode('/', ltrim( $url, '/'));
  
        if($url[0]){
            return [ 
                'controller' => self::getControllerName($url[0]), 
                'method'     => self::getMethodName ( $url[1] ? $url[1] :  'index') 
            ];
        }
        else{
            return [ 
                'controller' =>  self::getControllerName()	, 
                'method'     => self::getMethodName()
            ];
        }
        
    }

    /**
     * Gets the controller name from the URL.
     *
     * @param string $url The URL.
     * @return string The controller name.
     */
    private function getControllerName($url = null): string {
        return self::CONTROLLER_NAMESPACE . ( $url ? ucfirst($url) : 'Default' ). 'Controller';
    }

    /**
     * Gets the method name from the URL.
     *
     * @param string $url The URL.
     * @return string The method name.
     */
    private function getMethodName($url = null): string {
        return 'action' . ( $url ? ucfirst($url) : 'Index' );
    }
}

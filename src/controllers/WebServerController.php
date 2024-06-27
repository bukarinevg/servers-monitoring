<?php
namespace app\controllers;

use app\source\attribute\http\RouteAttribute;

class WebServerController extends \app\source\controller\AbstractController
{
    /**
     * WebServerController index
     *
     * @return string
     */
    
    #[RouteAttribute(path: '/', method: 'GET')]
    public function actionIndex(): void
    {
        echo 'WebServerController index';
        return;
    }
}
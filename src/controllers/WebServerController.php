<?php
namespace app\controllers;

use app\source\attribute\http\RouteAttribute;
use app\models\WebServerModel;

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

    /**
     * WebServerController post
     *
     * @return void
     */
    #[RouteAttribute(path: '/post', method: 'POST')]
    public function actionAdd(): void
    {
        $webServerModel = new WebServerModel();
        $webServerModel->load($this->app->getRequest());
        $webServerModel->port = 80;
        $webServerModel->save();
        return;
    }
}
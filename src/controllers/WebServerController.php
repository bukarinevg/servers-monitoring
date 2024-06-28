<?php
namespace app\controllers;

use app\source\attribute\http\RouteAttribute;
use app\models\WebServerModel;

class WebServerController extends \app\source\controller\AbstractController
{

    /**
     * WebServerController get
     *
     * @return void
     */
    #[RouteAttribute(path: '/get', method: 'GET')]
    public function actionGet($id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $webServerModel = WebServerModel::find($id);
        echo $webServerModel->toJson();
        return;
    }

    /**
     * WebServerController post
     *
     * @return void
     */
    #[RouteAttribute(path: '/post', method: 'POST')]
    public function actionPost(): void
    {
        $webServerModel = new WebServerModel();
        $webServerModel->load($this->app->getRequest());
        $webServerModel->save();
        return;
    }

    /**
     * WebServerController put
     *
     * @return void
     */
    #[RouteAttribute(path: '/put', method: 'PUT')]
    public function actionPut(int $id): void
    {
        $webServerModel = WebServerModel::find($id);
        $webServerModel->load($this->app->getRequest());
        $webServerModel->save();
        return;
    }
}
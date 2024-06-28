<?php
namespace app\controllers\api\v1;

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
    public function actionPost(): int 
    {
        $webServerModel = new WebServerModel();
        $webServerModel->load($this->app->getRequest());
        $webServerModel->save();
        echo $webServerModel->toJson();
        return $webServerModel->id;
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
        echo $webServerModel->toJson();
        return;
    }

    /**
     * WebServerController delete
     *
     * @return void
     */
    #[RouteAttribute(path: '/delete', method: 'DELETE')]
    public function actionDelete(int $id): void
    {
        $webServerModel = WebServerModel::find($id);
        $webServerModel->delete();
        return;
    }
}
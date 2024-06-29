<?php
namespace app\controllers\api\v1;

use app\source\attribute\http\RouteAttribute;
use app\models\WebServerModel;
use app\models\WebServerWorkModel;

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
        $webServerWorks = WebServerWorkModel::findBy(['web_server_id' => $id]);
        
        $response = [
            'web_server' => $webServerModel->toArray(
                ['name', 'path', 'port', 'status_message', 'created_at', 'updated_at']
            ),
            'web_server_works' => []
        ];
        $webServerWorks = array_reverse($webServerWorks);
        foreach ($webServerWorks as $key => $webServerWork) {
            if($key > 9) break;
            $response['web_server_works'][] = $webServerWork->toArray(
                ['status', 'status_code', 'message', 'created_at', ]
            );
        }
        echo json_encode($response);
        return;
    }

    /**
     * WebServerController getAll
     *
     * @return void
     */
    #[RouteAttribute(path: '/get-all', method: 'GET')]
    public function actionGetAll(): void
    {
        $webServerModel = WebServerModel::findAll();
        $output = [];
        foreach ($webServerModel as $model) {
            $output[] = $model->toArray(
                ['name', 'path', 'port', 'status_message', 'created_at', 'updated_at']
            );
        }
        echo json_encode($output);
        return;
    }

    /**
     * WebServerController getHistory
     *
     * @return void
     */
    #[RouteAttribute(path: '/get-history', method: 'GET')]
    public function actionGetHistory($id): void
    {
        $webServerWorks = WebServerWorkModel::findBy(['web_server_id' => $id]);
        $output = [];
        foreach ($webServerWorks as $webServerWork) {
            $output[] = $webServerWork->toArray(
                ['status', 'status_code', 'message', 'created_at']
            );
        }
        echo json_encode($output);
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
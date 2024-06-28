<?php 
namespace app\controllers;


class DefaultController extends \app\source\controller\AbstractController
{

    /**
     * DefaultController index
     *
     * @return string
     */
    public function actionIndex(): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        $errorMessage = [
            'error' => 'Not Found',
            'message' => 'Set controller and method in the URL'
        ];
        echo json_encode($errorMessage);
        return;
    }
}

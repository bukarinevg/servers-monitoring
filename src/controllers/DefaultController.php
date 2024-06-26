<?php 
namespace app\controllers;


class DefaultController extends \app\source\controller\AbstractController
{

    /**
     * DegaultController index
     *
     * @return string
     */
    public function actionIndex(): string
    {
        echo 'DefaultController index';
        return 'DefaultController index';
    }
}

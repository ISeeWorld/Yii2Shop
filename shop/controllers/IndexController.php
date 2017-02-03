<?php
namespace app\controllers;

use yii\web\Controller;

class IndexController extends Controller
{

    public function actionIndex()
    {
        // $this->layout = false;
        //使原有的模版布局变为空 启用新的模版布局
        $this->layout = "layout1";
        return $this->render('index');
    }

}

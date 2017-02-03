<?php
namespace app\controllers;

use yii\web\Controller;

class OrderController extends Controller
{
    public $layout = false;
    public function actionCheck()
    {
        $this->layout = "layout1";
        return $this->render('check');
    }
    public function actionIndex()
    {
        $this->layout = "layout2";
        return $this->render('index');
    }
}

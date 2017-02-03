<?php
namespace app\controllers;

use yii\web\Controller;

class ProductController extends Controller
{
    public $layout = false;
    public function actionIndex()
    {
        // $this->layout = false;
        $this->layout = "layout2";
        return $this->render('index');
    }
    public function actionDetail()
    {
        $this->layout = "layout2";
        return $this->render('detail');
    }
}

<?php
namespace app\controllers;

use app\models\Test;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {

        $test = new Test;
        $data = $test->find()->one();
        return $this->render('index', array('mdata' => $data));

    }
}

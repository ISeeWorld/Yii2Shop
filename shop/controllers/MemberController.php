<?php
namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;

class MemberController extends Controller
{
    // public $layout = false;
    public function actionAuth()
    {
        $this->layout = "layout2";
        $auth_model = new User;
        if ($auth_model->login($data)) {
            $this->redirect('[index/index]');
        }
        return $this->render('auth',['auth_model'=>$auth_model]);
    }
    public function actionReg()
    {
        $this->layout = "layout2";
        $reg_model    = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($reg_model->regByMail($post)) {
                Yii::$app->session->setFlash('info', '邮件发送成功！');

            }
        }
        return $this->render('auth', ['reg_model' => $reg_model]);
    }

}

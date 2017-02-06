<?php
namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;

class MemberController extends Controller
{
    // public $layout = false;
    // 系统登录方法
    // 2017年2月6日 21:01:16
    public function actionAuth()
    {
        $this->layout = "layout2";
        $auth_model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($auth_model->login($post)) {
            // $url = Yii::$app->session->getFlash('referrer');
            // return $this->redirect($url);
            return $this->redirect(['member/auth']);
        } 
        }
        return $this->render('auth',['model'=>$auth_model]);
    
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
        return $this->render('auth', ['model' => $reg_model]);
    }
    public function actionLogout(){
        $model = new User;
        if ($model->logout()) {
            // $this->redirect(['index/index']);
            // 用法独特，后期需要研究下
            return $this->goBack(Yii::$app->request->referrer);
        }
    }
}

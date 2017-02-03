<?php
namespace app\modules\controllers;

use app\modules\models\Admin;
use Yii;
use yii\web\Controller;

class PublicController extends Controller
{
    public function actionLogin()
    {
        $this->layout = false;
        $admin_model  = new Admin;
        if (Yii::$app->request->isPost) {
            //bug post get
            $post = Yii::$app->request->post();
            // var_dump($post);
            if ($admin_model->login($post)) {
                $this->redirect(['default/index']);
                Yii::$app->end();
            }
        }

        return $this->render('login', ['admin_model' => $admin_model]);
        //bug $admin_model错误发生
    }
    /**
     * logout
     * 2016年10月6日 00:04:26
     * @return [type] [description]
     */
    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        // 删除session的操作
        if (!Yii::$app->session['admin']['isLoin']) {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        $this->goback();

    }
    /**
     * 找回密码的功能函数
     * 2016年10月6日 10:11:03
     * @return [type] [description]
     */
    public function actionSeekpwd()
    {
        $this->layout = false;
        $admin_model  = new Admin;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($admin_model->seekPass($post)) {
                Yii::$app->session->setFlash('info', '邮件发送成功！');
            }
        }
        return $this->render('seekpwd', ['admin_model' => $admin_model]);
    }

}

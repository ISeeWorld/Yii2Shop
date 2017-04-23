<?php

namespace app\modules\controllers;

use yii\web\Controller;
use Yii;
/**
 * 统一管理后台用户登录
 * 2017年4月23日 16:22:58
 * 登录内容的具体判断
 */
class CommonController extends Controller
{
    public function init()
    {
        if (Yii::$app->session['admin']['isLogin'] != 1) {
            return $this->redirect(['/admin/public/login']);
        }
    }
}

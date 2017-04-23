<?php
namespace app\modules\controllers;

use app\modules\models\Admin;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use app\modules\controllers\CommonController;

class ManageController extends CommonController
{

    public function actionMailchangepass()
    {
        $this->layout = false;
        $time         = Yii::$app->request->get('timestamp');
        $adminuser    = Yii::$app->request->get('adminuser');
        $token        = Yii::$app->request->get('token');
        // 从传递来的数据中提取有效参数
        $admin_model = new Admin;
        $mytoken     = $admin_model->createToken($time, $adminuser);
        if ($token != $mytoken) {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        if (time() - $time > 3000) {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        if (Yii::$app->request->isPost) {
            $admin_model->adminuser = $adminuser;
            // bug 问题严重 需要摸索流程化调试的方法 2016年10月6日 19:59:33
            $post = Yii::$app->request->post();
            if ($admin_model->changePass($post)) {
                Yii::$app->session->setFlash('info', '密码修改成功！');
                // var_dump($post);
                $this->redirect(['public/login']);
                Yii::$app->end();
            }
        }

        return $this->render('mailchangepass', ['admin_model' => $admin_model]);
    }
    /**
     * 分页显示管理员
     * @return [type] [description]
     */
    public function actionManagers()
    {
        $this->layout = 'layout1';
        $model        = Admin::find();
        $count        = $model->count();
        // 得到管理员总数
        $pagesize = Yii::$app->params['mypageSize']['manage'];
        //使用config下的配置文件进行配置
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pagesize]);
        // 分页类的用法
        $manager_data = $model->offset($pager->offset)->limit($pager->limit)->all();
        // 需要细致掌握AR模型的数据操作
        return $this->render('managers', ['manager_data' => $manager_data, 'pager' => $pager]);
        // 模型传递分页类实例化出来的对象pager
    }
    /**
     * new adminuser register
     * 新用户注册
     * 2016年10月7日 21:46:05
     * @return [type] [description]
     */
    public function actionReg()
    {
        $this->layout = 'layout1';
        $admin_model  = new Admin;
        if (Yii::$app->request->isPost) {
            // echo "post get";
            $post = Yii::$app->request->post();
            // var_dump($post);
            if ($admin_model->adminAdd($post)) {
                Yii::$app->session->setFlash('info', '新管理员添加成功！');

                $this->redirect(['manage/managers']);
                Yii::$app->end();
            } else {
                Yii::$app->session->setFlash('info', '新管理员添加失败！');
            }
        }
        return $this->render('reg', ['admin_model' => $admin_model]);
    }
    /**
     * 删除用户
     * @return [type] [description]
     */
    public function actionDel()
    {
        $adminid = (int) Yii::$app->request->get('adminid');
        // 强制转换为INT 为了安全性的提升
        if (empty($adminid)) {
            return false;
        }
        $admin_model = new Admin;
        if ($admin_model->deleteAll('adminid = :id', [':id' => $adminid])) {
            //删除数据的方法
            Yii::$app->session->setFlash('info', '删除成功！');
            $this->redirect(['manage/managers']);
        }
    }
    /**
     * 修改管理员邮箱
     * 2016年10月8日 23:05:05
     * @return [type] [description]
     */
    public function actionChangeemail()
    {
        $this->layout = 'layout1';
        $admin_model  = Admin::find()->where('adminuser = :user', [':user' => Yii::$app->session['admin']['adminuser']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($admin_model->changeEmail($post)) {
                Yii::$app->session->setFlash('info', '电子邮箱成功！');
                $this->redirect(['manage/managers']);
            }
        }
        return $this->render('changeemail', ['admin_model' => $admin_model]);
    }
    /**
     * 修改管理员密码
     * 二〇一六年十月八日 23:04:49
     * @return [type] [description]
     */
    public function actionChangepass()
    {
        $this->layout           = 'layout1';
        $admin_model            = Admin::find()->where('adminuser = :user', [':user' => Yii::$app->session['admin']['adminuser']])->one();
        $admin_model->adminpass = "";
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($admin_model->changePass($post)) {
                Yii::$app->session->setFlash('info', '管理员密码修改成功！');
                $this->redirect(['manage/managers']);
            }

        }
        return $this->render('changepass', ['admin_model' => $admin_model]);
    }

}

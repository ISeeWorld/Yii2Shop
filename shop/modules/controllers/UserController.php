<?php
namespace app\modules\controllers;

use app\models\profile;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;

/**
 *新的控制器
 *2017年1月4日
 */
class UserController extends Controller
{
/**
 * 在后台自动添加新用户
 * 用户注册
 * 2017年1月29日 20:42:06
 */
    public function actionReg()
    {
        $this->layout = 'layout1';
        $user_model   = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($user_model->reg($post)) {
                Yii::$app->session->setFlash('info', '会员添加成功！');
            } else {
                Yii::$app->session->setFlash('info', '会员添加失败！');
            }

        }
        $user_model->userpass = "";
        $user_model->repass   = "";

        return $this->render('reg', ['user_model' => $user_model]);
    }
    // 显示会员列表  进行会展示 重点学习分页类的使用
    // 2017年1月31日 23:18:38
    public function actionUsers()
    {
        $this->layout = "layout1";
        $user_model   = User::find()->joinWith('profile');
        // 联合查询 2017年2月3日
        $count    = $user_model->count();
        $pageSize = Yii::$app->params['mypageSize']['user'];
        $pager    = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $users    = $user_model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('users', ['users' => $users, 'pager' => $pager]);
    }
    //用户删除操作问题
    //2017年2月3日 20:50:12
    //
    public function actionDel()
    {
        try {
            $userid = (int) Yii::$app->request->get('userid');
            // 获取将要删除的用户ID
            if (empty($userid)) {
                throw new \Exception();
            }
            $trans = Yii::$app->db->beginTransaction();
            // 开始事物进行  2017年2月3日 20:51:15
            if ($obj = Profile::find()->where('userid = :id', [':id' => $userid])->one()) {
                $res = Profile::deleteAll('userid = :id', [':id' => $userid]);
                if (empty($res)) {
                    throw new \Exception();
                }
            }
            if (!User::deleteAll('userid = :id', [':id' => $userid])) {
                throw new \Exception();
            }
            $trans->commit();
            // 事物结束
        } catch (\Exception $e) {
            if (Yii::$app->db->getTransaction()) {
                $trans->rollback();
            }
            // 异常处理
        }
        $this->redirect(['user/users']);
    }

}

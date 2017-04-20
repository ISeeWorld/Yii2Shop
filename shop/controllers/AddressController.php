<?php 
namespace app\controllers;
use app\controllers\CommonController;
use app\models\User;
use app\models\Category;
use app\models\Order;
use app\models\Product;
use app\models\Address;

use Yii;

/**
* 2017年4月20日 09:07:21
* 地址管理模块
*/
class AddressController extends CommonController
{
    
    public function actionAdd()
    { 
        if (!Yii::$app->session['isLogin']) {
          return $this->redirect(['member/auth']);
        }
        // 判明是否登录
        // $orderid = Yii::$app->request->get('orderid');
        $username = Yii::$app->session['loginname'];
        $userid= User::find()->where('username= :name or useremail = :email',[':name'=>$username,':email'=>$username])->one()->userid;
        if (!$userid) {
            throw new \Exception("无此用户");       
        }
        echo $userid;
        echo gettype($userid);
        if (Yii::$app->request->isPost) {
           $post = Yii::$app->request->post();
           $post['userid'] = (int)$userid;
           $post['Address']= $post['address1'].$post['address2'];
           $data['Address']= $post;
           $addressmodel = new Address;
           //var_dump($data);
           // $addressmodel->add($data);
           if (!$addressmodel->add($data)){
               throw new \Exception("保存数据失败");
    
           }     
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionDel()
    {
        if (!Yii::$app->session['isLogin']) {
          return $this->redirect(['member/auth']);
        }
        // 判明是否登录
        // $orderid = Yii::$app->request->get('orderid');
        $username = Yii::$app->session['loginname'];
        $userid= User::find()->where('username= :name or useremail = :email',[':name'=>$username,':email'=>$username])->one()->userid;
        $addressid = Yii::$app->request->get('addressid');
        //判断用户是否是属于同一个人
        if (!Address::find()->where('userid = :uid and username = :name',[':uid'=>$userid,':name'=>$username])) {
            throw new \Exception('数据校验错误');
        }
        Address::deleteAll('addressid = :aid',[':aid'=>$addressid]);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}












 ?>
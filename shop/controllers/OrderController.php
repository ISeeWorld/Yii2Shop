<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use app\models\Category;
use app\models\Order;
use app\models\Orderdetail;

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
    /**
     * 用户添加操作
     * 2017年3月4日 16:02:49
     * @return [type] [description]
     */
    public function actionAdd()
    {
        if (!Yii::$app->session['isLogin']) {
          return $this->redirect(['member/auth']);
        }
        //开始事务处理 防止回滚
        //2017年3月4日 16:22:13
        $transaction = Yii::$app->db->beginTransaction();
        try{
           if (Yii::$app->request->isPost) {
            $postdata = Yii::$app->request->post();
            $ordermodel = new Order;
            $ordermodel->scenario = 'add';
            $username = Yii::$app->session['loginname'];
            $usermodel= User::find()->where('username= :name or useremail = :mail',[':user'=>$username,':email'=>$username])->one();
            if (!usermodel) {
               throw new Exception("无此用户");        
            }
            $userid = $usermodel->userid;
            $ordermodel->userid = $userid;
            $ordermodel->status =  Order::CREATEORDER;
            $ordermodel->createtime = time();

            if(!$ordermodel){
                throw new Exception("订单保存错误");
            }
            // 新用法 获取用户的主键值
            $orderid = $ordermodel->getPrimaryKey();
            //数组遍历 如何进行 
            //2017年3月4日 18:08:38
            //数组知识必须补充
            foreach ($postdata as $pro) {
               $detailmodel = new Orderdetail;
               $pro['orderid'] = $orderid;
               $pro['createtime'] = time();
               $data['orderDetail'] = $pro;
               if (!$detailmodel->add($data)) {
                 throw new Exception("dteail error!");
               }
            }
            //失败后要删除
            Cart::deleteAll('productid = :pid' , [':pid' => $product['productid']]);
            //更新数据 2017年3月4日 18:15:04
            Product::updateAllCounters(['num' => -$product['productnum']], 'productid = :pid', [':pid' => $product['productid']]);

           }
           $transaction->commit();
           // 提交数据
        }
        catch(\Exception $e){
            return $transaction->rollback();
            // 回滚
            $this->redirect(['cart/index']);
        }

        return $this->redirect(['order/check', 'orderid' => $orderid]);

    }






}

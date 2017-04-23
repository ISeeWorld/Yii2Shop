<?php 
namespace app\modules\controllers;
use yii\web\Controller;
use app\models\Order;
use app\models\OrderDetail;
use yii\data\pagination;
use app\models\User;
use app\models\Address;
use app\models\Product;


use Yii;


class OrderController extends Controller
{
    /**
     * 显示列表 
     * 2017年4月22日 11:30:21
     * @return [type] [description]
     */
    public function actionList()
    {
     // 订单编号   下单人     收货地址    快递方式    订单总价    商品列表    订单状态    操作
     $this->layout = 'layout1';
     $model = Order::find();
     $count = $model->count();
     $pageSize = Yii::$app->params['mypageSize']['order'];
     $pager = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
     $data = $model->offset($pager->offset)->limit($pager->limit)->all();
     $data = Order::getDetails($data);
     // var_dump($data);
     return $this->render('list',['orders'=>$data,'pager'=>$pager]);
    }

    /**
     * 显示列表具体内容
     * 2017年4月23日 10:57:26
     * @return [type] [description]
     */
    public function actionDetail()
    {

      $this->layout = 'layout1';
      $data = [];
      if (Yii::$app->request->isGet) {
        $orderid = Yii::$app->request->get('orderid');
        if ($orderid) {
          $data['orderid'] = $orderid;
          $ordermodel = Order::find()->where('orderid = :oid',[':oid'=>$orderid])->one();
          $username = User::find()->where('userid = :uid',[':uid' =>$ordermodel->userid] )->one()->username;
          $data['username'] = $username;
          $data['address']=Address::find()->where('addressid = :oid',[':oid'=>$ordermodel->addressid])->one()->address;
          $data['amount'] = $ordermodel->amount;
          $data['express'] = Yii::$app->params['express'][$ordermodel->expressid];
          $data['expressno'] =$ordermodel->expressno;
          $data['status'] =Order::$status[$ordermodel->status];
          $pmodel = OrderDetail::find()->where('orderid =:oid',[':oid'=>$orderid])->one();
          $data['num'] = $pmodel->productnum;
          $data['productid'] = $pmodel->productid;
          $data['cover']=Product::find()->where('productid =:pid',[':pid'=>$pmodel->productid])->one()->cover;
        }
      }
      return $this->render('detail',['data'=>$data]);

    }
    /**
     * 显示发货状态
     * 一个经典的数据交互的模型 例子 
     * 2017年4月23日 15:33:02
     * @return [type] [description]
     */
    public function actionSend()
    {
         $this->layout = 'layout1';
         $orderid = Yii::$app->request->get('orderid');
         $ordermodel = Order::find()->where('orderid = :oid',[':oid'=>$orderid])->one();
         $ordermodel ->scenario = 'send';
         // 此处是逻辑模块划分点
         // 2017年4月23日 15:33:27
         if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $ordermodel->status = Order::SENDED;
            if($ordermodel->load($post) && $ordermodel->save())
            {
                Yii::$app->session->setFlash('info','发货成功');
            }
         }

         return $this->render('send',['model'=>$ordermodel]);
    }




}



 ?>
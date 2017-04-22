<?php 
namespace app\modules\controllers;
use yii\web\Controller;
use app\models\Order;
use app\models\OrderDetail;
use yii\data\pagination;
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
     // $data = Order::getDetails($data);
     // var_dump($data);
     return $this->render('list',['orders'=>$data,'pager'=>$pager]);
    }

    public function actionSend()
    {

    }

    public function actionDetail()
    {

    }




}



 ?>
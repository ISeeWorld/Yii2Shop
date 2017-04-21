<?php 
namespace app\modules\controllers;
use yii\web\Controller;

class OrderController extends Controller
{

    public function actionList()
    {
     // 订单编号   下单人     收货地址    快递方式    订单总价    商品列表    订单状态    操作
     $this->layout = 'layout1';

     return $this->render('list');
    }





}



 ?>
<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{

      const CREATEORDER = 0;
      const CHECKORDER = 100;
      const PAYFAILED = 203;
      const PAYSUCCESS=202;
      const SENDED=220;
      const RECEIVED = 260;

      public static $status =[
        self::CREATEORDER=>'订单初始化',
        self::CHECKORDER =>'待支付',
        self::PAYFAILED  =>'支付失败',
        self::PAYSUCCESS  =>'支付失败',
        self::SENDED  =>'已经发货',
        self::RECEIVED  =>'订单完成',
      ];


      public static function tableName()
      {
        return "{{%order}}";
      }


      public function rules()
      {
        return [
            [['userid', 'status'], 'required', 'on' => ['add']],
            ['expressno', 'required', 'message' => '请输入快递单号', 'on' => 'send'],
            ['createtime', 'safe', 'on' => ['add']],
            // [['addressid', 'expressid', 'amount', 'status'], 'required', 'on' => ['update']],
            [['expressid', 'amount', 'status','addressid'], 'required', 'on' => ['update']],
        ];
      }


      public function attributeLabels()
      {
        return [];
      }

      /**
       * 获取商品及订单详细信息
       * 2017年4月22日 12:14:44
       */    
      public static function getDetails($order)
      {
        $oderdetails = OrderDetail::find()->where('orderid = :oid',[':oid'=>$order->orderid])->all();
        $data = [];
        // var_dump($orderdetails);
        foreach ($orderdetails as $detail) {
              
        }
        return orders;
      }
}














 ?>
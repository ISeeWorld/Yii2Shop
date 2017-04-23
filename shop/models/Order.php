<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;
use app\models\Product;
use app\models\Address;
use app\models\OrderDetail;
use app\models\Order;


class Order extends ActiveRecord
{
      public $username;
      public $product;
      public $products;
      public $address = "";
      public $zhstatus;
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
        self::PAYSUCCESS  =>'支付成功',
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
        return [
         'expressno'=>'订单编号',
        ];
      }

     /**
      * 2017年4月22日 23:25:15
      * @param  [type] $orders [description]
      * @return [type]         [description]

      /**
       * 获取商品及订单详细信息
       * 2017年4月22日 12:14:44
       */    
      public static function getDetails($orders)
      {
        foreach ($orders as $order) {
          $details = OrderDetail::find()->where('orderid = :oid',[':oid'=>$order->orderid])->all();
        $products = [];
        // 查询商品详情
        // 2017年4月22日 22:42:30
         foreach($details as $detail) {
            $product = Product::find()->where('productid = :pid', [':pid' => $detail->productid])->one();
            if (empty($product)) {
                continue;
            }
            $product->num = $detail->productnum;
            $products[] = $product;
        }
        $order->product = $products;
        // 查询用户姓名
        // 2017年4月22日 22:53:00
        $user = User::find()->where('userid = :uid',[':uid' => $order->userid])->one();
        if ($user) {
          $order->username = $user->username;
        }
        
        //查询地址同时记录
        //2017年4月22日 22:47:24
        $address = Address::find()->where('addressid = :oid',[':oid'=>$order->addressid])->one();
        if (!empty($address)) {
          $order->address = $address->address;
          //TEXT VARCHAR 字段类型比较
          //注意区分   
        }else
        {
          $order->address = "未知";
        }
        // 数组学习掌握不行  调试技巧不到位
        // 2017年4月23日 10:40:53
        //  PHP Recoverable Error – yii\base\ErrorException
        // Object of class app\models\Address could not be converted to string
        // $order->$address !!!!!!!!!!!!!!!!!
        // 错误写法！！！！！！！！！！！！！！！
        // 对象属性 $order->$address $order->address才能如此表达！
        // ！！！！！！！！！！！！！！！！！！！！！！！！！！！
        // fuck!
         $order->zhstatus = self::$status[$order->status];

        }
        return $orders;
      }
      /**
       * 获取商品
       * 2017年4月23日 15:46:50
       * @param  [type] $userid [description]
       * @return [type]         [description]
       */
      public static function getProducts($userid)
    {
        $orders = self::find()->where('status > 0 and userid = :uid', [':uid' => $userid])->orderBy('createtime desc')->all();
        foreach($orders as $order) {
            $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $order->orderid])->all();
            $products = [];
            foreach($details as $detail) {
                $product = Product::find()->where('productid = :pid', [':pid' => $detail->productid])->one();
                if (empty($product)) {
                    continue;
                }
                $product->num = $detail->productnum;
                $product->price = $detail->price;
                $product->cate = Category::find()->where('cateid = :cid', [':cid' => $product->cateid])->one()->title;
                $products[] = $product;
            }
            $order->zhstatus = self::$status[$order->status];
            $order->products = $products;
        }
        return $orders;
    }
}














 ?>
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
    
        ];
      }


      public function attributeLabels()
      {
        return [];
      }
    
}














 ?>
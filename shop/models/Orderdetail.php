<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Orderdetail extends ActiveRecord
{
      public static function tableName()
      {
        return "{{%orderdetail}}";
      }


      public function rules()
      {
        return [
    
        ];
      }


      public function attributeLabels()
      {
        return [

        ];
      }
    

    public function add($data)
    {
        if ($this->load($data)) {
          return true;
        }
        return false;
    }
}














 ?>
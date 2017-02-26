<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
  public static function tableName()
  {
    return "{{%cart}}";
  }
  public function rules()
  {
    return [];
  }
  public function attributeLabels()
  {
    return [];
  }
 


}














 ?>
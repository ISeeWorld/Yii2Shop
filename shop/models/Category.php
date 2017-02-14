<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Category extends ActiveRecord
{

    public static function  tableName()
    {
        return "{{%category}}";
    }

    public function rules(){
         return [
          
         ];
    }
    
    public function attributeLabels(){
   
    return [
           'parentid' =>'顶级分类',
           'title'    =>'标题'
    ];
    }

}







 ?>
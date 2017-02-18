<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    
  const AK = 'f1NQz9_Zmd3zS0a079YJ1-HF3tLj3Ce3I9ekXtMF';
  const SK = 'ciIWIlTJg5kIc2NbqXBgQyvYdJR8fxEITcIUzOFT';
  const DOMAIN = 'olknm0wzh.bkt.clouddn.com';
  const bucket = 'yii2-imooc-shop';

  public static function tableName()
  {
    return "{{%product}}";
  }


  public function rules()
  {
    return [];

  }

  public function attributeLabels()
  {
    return [];
  }




/*
Usage
七牛云存储使用方法
<?php
$ak = 'sss';
$sk = 'sss';
$domain = 'http://demo.domain.com/';
$bucket = 'demo';
use crazyfd\qiniu\Qiniu;
$qiniu = new Qiniu($ak, $sk,$domain, $bucket);
$key = time();
$qiniu->uploadFile($_FILES['tmp_name'],$key);
$url = $qiniu->getLink($key);
*/



}






 ?>
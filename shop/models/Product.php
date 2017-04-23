<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
  public $cate;
  const AK = 'f1NQz9_Zmd3zS0a079YJ1-HF3tLj3Ce3I9ekXtMF';
  const SK = 'ciIWIlTJg5kIc2NbqXBgQyvYdJR8fxEITcIUzOFT';
  const DOMAIN = 'olknm0wzh.bkt.clouddn.com';
  const BUCKET = 'yii2-imooc-shop';

  public static function tableName()
  {
    return "{{%product}}";
  }


  public function rules()
  {
    return [
    ['cateid','required','message'=>'分类ID不能为空'],
    ['title','required','message'=>'标题名称不能为空'],
    ['descr','required','message'=>'描述不能为空'],
    //售价的验证问题
    [['price','saleprice'],'required','message'=>'价格不能为空'],
    //数字的验证规则
    ['num', 'integer', 'min' => 0, 'message' => '库存必须是数字'],
    //布尔值检查
    [['issale','ishot', 'pics', 'istui'],'safe'],
    // 封面的规则
    [['cover'], 'required'],
    ];

  }

  public function attributeLabels()
  {
    return [
    'cateid'=>'分类ID',
    'title' =>'标题',
    'descr' =>'描述',
    'price' =>'价格',
    'ishot' =>'是否热卖',
    'issale'=>'是否促销',
    'saleprice' =>'折扣价',
    'ison' =>'是否上架',
    'istui'=>'是否推荐',
    'cover'=>'封面',
    'pics' =>'图集',
    'num'  =>'数量'
    ];
  }
  /**
   * 数据方法 添加数据
   * @param [type] $data [description]
   */
  public function add($data)
  {
    if ($this->load($data)&&$this->save(false)) {
        return true;
    }
    return false;
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
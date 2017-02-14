<?php 
namespace app\modules\controllers;

use app\models\Category;
use Yii;
use yii\web\Controller;

class CategoryController extends Controller
{
   /**
    * 分类列表显示函数
    * 2017年2月14日 23:03:49
    * @return [type] [description]
    */
   public function actionList(){
    
    $this->layout = 'layout1';
    $models = new Category;
    return $this->render('cates',['model'=>$models]);
   }
 
   /**
    * 顶级分类添加
    * 2017年2月14日 23:04:17
    * @return [type] [description]
    */
   public function actionAdd(){
   $this->layout = 'layout1';
   $list = ['顶级分类添加'];
   $models = new Category;
   return $this->render('add',['model'=>$models,'list'=>$list]);

   }






}









 ?>
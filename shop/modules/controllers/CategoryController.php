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
    $list = $models->getTreeList();
    return $this->render('cates',['model'=>$models,'cates'=>$list]);
   }
 
   /**
    * 顶级分类添加
    * 2017年2月14日 23:04:17
    * @return [type] [description]
    */
   public function actionAdd()
   {
       $models = new Category;
       $this->layout = 'layout1';
       $list = $models->getOptions();
       if (Yii::$app->request->isPost) {
           $post = Yii::$app->request->post();
           if ($models->add($post)) {
              Yii::$app->session->setFlash('info','添加成功！');
           }
       }
       return $this->render('add',['model'=>$models,'list'=>$list]);

   }
   
   public function actionDel()
   {
      $models = new Category;
   }

    /**
     * 编辑分类
     * 2017年2月16日 21:43:31
     * @return [type] [description]
     */
   public function actionEdit()
   {
     $this->layout = "layout1";
     $id = Yii::$app->request->get('cateid');
     $model = Category::find()->where('cateid = :id',[':id'=>$id])->one();
     if (Yii::$app->request->isPost) {
         $post = Yii::$app->request->post();
         if ($model->load($post) && $model->save()) {
             Yii::$app->session->setFlash('info','修改成功!');
         }
     }
     $list = $model->getOptions();
     return $this->render('add',['model'=>$model,'list'=>$list]);

   }



}









 ?>